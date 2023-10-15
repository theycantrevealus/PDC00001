<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/pdfjs/pdf2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(async function() {

        var spri_noKartu_noSep = "";
        var selectedKartu = "";
        var selected_SPRI = "";

        var refreshData = 'N';
        var SPRINo = "";
        var MODE = "ADD";
        var JenisSearch = "";

        var clickedTab = [1];
        var ListRencanaKontrolInap, NoSuratKontrolInap;

        var getUrl = __BPJS_SERVICE_URL__ + "rc/sync.sh/listrencanakontrol/?tglawal=2023-01-01&tglakhir=2023-01-30&filter=1";

        //ZONE LIST
        $("#tglawal_list_kontrol").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#tglakhir_list_kontrol").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());
        $("#btn_search_list_kontrol").click(function() {
            getUrl = __BPJS_SERVICE_URL__ + "rc/sync.sh/listrencanakontrol/?tglawal=" + $("#tglawal_list_kontrol").val() + "&tglakhir=" + $("#tglakhir_list_kontrol").val() + "&filter=" + $("#filter_list_kontrol option:selected").val();
            MODE = "SEARCH_ListRencanaKontrolInap";
            ListRencanaKontrolInap.ajax.url(getUrl).load();
        });

        //ZONE LIST BY NO KARTU
        $("#search_tgl_no_kartu").datepicker({
            changeMonth: true,
            changeYear: true,
            // showButtonPanel: true,
            dateFormat: 'MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());
        var parse_search_tgl_no_kartu = new Date($("#search_tgl_no_kartu").datepicker("getDate"));
        $("#btn_search_no_kartu").click(function() {
            getUrl = __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrolbykartu/?bulan=" + str_pad(2, parse_search_tgl_no_kartu.getMonth() + 1) + "&tahun=" + parse_search_tgl_no_kartu.getFullYear() + "&nokartu=" + $("#search_no_kartu").val() + "&filter=" + $("#search_filter_no_kartu option:selected").val();
            MODE = "SEARCH_ListRencanaKontrolInap";
            ListRencanaKontrolInap.ajax.url(getUrl).load();
        });
        $('#alert-sprirk-container').hide();


        // ZONE NO SURAT
        var getUrlNoSuratKontrolInap = __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol?nosuratkontrol=0000000000000000000";

        $("#btn_search_NoSuratKontrolInap").click(function() {
            getUrlNoSuratKontrolInap = __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol/?nosuratkontrol=" + $("#nosurat_NoSuratKontrolInap").val();
            MODE = "SEARCH_NoSuratKontrolInap";
            NoSuratKontrolInap.ajax.url(getUrlNoSuratKontrolInap).load();
        });
        $('#alert-NoSuratKontrolInap-container').hide();

        //INIT DATATABLE
        ListRencanaKontrolInap = $("#table-spri").DataTable({
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
                        if (MODE === "SEARCH_ListRencanaKontrolInap") {
                            $('#alert-sprirk').text(response.metadata.message);
                            $('#alert-sprirk-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-sprirk-container').fadeOut();
                        return response.response.list;
                    }
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [
                [-1, 20, 50],
                ["All", 20, 50]
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
                        return row.noSuratKontrol;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (parseInt(row.jnsKontrol) === 1) ? "Inap" : "Kontrol";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglRencanaKontrol;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglTerbitKontrol;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.nama + " - " + row.noKartu;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSepAsalKontrol;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.namaPoliAsal;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.poliTujuan;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.kodeDokter + " - " + row.namaDokter;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-warning btn-sm btnPrintSPRI\" no-sep=\"" + row.noSep + "\" id=\"" + row.noSuratKontrol + "\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button class=\"btn btn-info btn-sm btnEditSPRI\" jnsKontrol=\"" + row.jnsKontrol + "\" no-sep=\"" + row.noSep + "\" id=\"" + row.noSuratKontrol + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSPRI\" jnsKontrol=\"" + row.jnsKontrol + "\" id=\"" + row.noSuratKontrol + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#tab-referensi-bpjs .nav-link").click(function(e) {
            var child = $(this).get(0).hash.split("-");
            child = parseInt(child[child.length - 1]);
            if (child === 1) {
                if (clickedTab.indexOf(child) >= 0) {
                    ListRencanaKontrolInap.ajax.reload();
                }
            } else if (child === 2) {
                if (clickedTab.indexOf(child) >= 0) {
                    NoSuratKontrolInap.ajax.reload();
                } else {
                    clickedTab.push(2);
                    NoSuratKontrolInap = $("#table-NoSuratKontrolInap").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlNoSuratKontrolInap,
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
                                    if (MODE === "SEARCH_NoSuratKontrolInap") {
                                        console.log(response.metadata.code);
                                        $('#alert-NoSuratKontrolInap').text(response.metadata.message);
                                        $('#alert-NoSuratKontrolInap-container').fadeIn();
                                    }
                                    return [];
                                } else {
                                    $('#alert-NoSuratKontrolInap-container').fadeOut();
                                    return [response.response];
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [-1, 20, 50],
                            ["All", 20, 50]
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
                                    return row.noSuratKontrol;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return (parseInt(row.jnsKontrol) === 1) ? "Inap" : "Kontrol";
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglRencanaKontrol;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglTerbit;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.nama + " - " + row.sep.noKartu;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.noSep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.namaPoliTujuan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kodeDokter + " - " + row.namaDokter;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-warning btn-sm btnPrintSPRI\" no-sep=\"" + row.sep.noSep + "\" id=\"" + row.noSuratKontrol + "\">" +
                                        "<i class=\"fa fa-print\"></i> Cetak" +
                                        "</button>" +
                                        "<button class=\"btn btn-info btn-sm btnEditSPRI\" jnsKontrol=\"" + row.jnsKontrol + "\"  no-sep=\"" + row.sep.noSep + "\" id=\"" + row.noSuratKontrol + "\">" +
                                        "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                        "</button>" +
                                        "<button class=\"btn btn-danger btnHapusSPRI\" jnsKontrol=\"" + row.jnsKontrol + "\" id=\"" + row.noSuratKontrol + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            }
        });

        $("body").on("click", ".btnHapusSPRI", function() {
            var no_surat = $(this).attr("id");
            var jnsKontrol = $(this).attr("jnsKontrol");

            Swal.fire({
                title: "BPJS Rencana Kontrol/Inap",
                text: "Hapus No. Surat " + no_surat + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rc/sync.sh/deleterc",
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
                        data: JSON.stringify({
                            "t_suratkontrol": {
                                "noSuratKontrol": no_surat,
                                "user": __MY_NAME__
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS Rencana Kontrol/Inap',
                                    'Rencana Kontrol/Inap Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    ListRencanaKontrolInap.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS Rencana Kontrol/Inap',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    ListRencanaKontrolInap.ajax.reload();
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


        $("#panel-peserta").hide();

        $("#txt_bpjs_spri_noSep").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#col_spri_noSep'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisep`,
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
                data: function(term) {
                    return {
                        nomorsep: term.term
                    };
                },
                cache: true,
                processResults: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_spri_noSep option").remove();
                        return [];
                    } else {
                        var data = [response.response];
                        return {
                            results: $.map(data, function() {
                                return {
                                    text: data[0].noSep,
                                    id: data[0].noSep,
                                    noKartu: data[0].peserta.noKartu,
                                    nama: data[0].peserta.nama,
                                    tglLahir: data[0].peserta.tglLahir
                                }
                            })
                        };
                    }
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            console.log(data);
            $("#txt_nama_peserta").val(data.nama + " - " + data.noKartu);
            $("#txt_tgllahir_peserta").val(data.tglLahir);
            $("#panel-peserta").fadeIn();

            refreshSpesialistik($("#txt_bpjs_spri_noSep").val());
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_noKartu").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No. Kartu tidak ditemukan";
                }
            },
            dropdownParent: $("#col_spri_noKartu"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}peserta/sync.sh/getpesertabynokartu`,
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
                        nomorkartu: term.term,
                        tglsep: $("#txt_bpjs_spri_tglRencanaKontrol").val()
                    };
                },
                processResults: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_spri_noKartu option").remove();
                        return [];
                    } else {
                        var data = response.response;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: data[0].noKartu,
                                    id: data[0].noKartu,
                                    nama: data[0].nama,
                                    nik: data[0].nik,
                                    tglLahir: data[0].tglLahir
                                }
                            })
                        };
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_peserta").val(data.nama + " - " + data.nik);
            $("#txt_tgllahir_peserta").val(data.tglLahir);
            $("#panel-peserta").fadeIn();

            refreshSpesialistik($("#txt_bpjs_spri_noKartu").val());
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_poliKontrol").select2({
            dropdownParent: $('#col_spri_poliKontrol')
        });

        $("#txt_bpjs_spri_kodeDokter").select2({
            dropdownParent: $('#col_spri_kodeDokter')
        });

        function refreshSpesialistik(nomor) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/listspesialistik/?jeniskontrol=" + $("#txt_bpjs_spri_jenis").val() + "&nomor=" + nomor + "&tglrencanakontrol=" + $("#txt_bpjs_spri_tglRencanaKontrol").val(),
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
                    $("#txt_bpjs_spri_poliKontrol option").remove();

                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_spri_poliKontrol").select2({
                            "language": {
                                "noResults": function() {
                                    return response.metadata.message;
                                }
                            }
                        });
                        $("#txt_bpjs_spri_poliKontrol").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodePoli,
                                text: "[Poli: " + data[a].kodePoli + " - " + data[a].namaPoli + "] [Kapasitas: " + data[a].kapasitas + "] [jlh.Rencana Kontrol & Rujukan: " + data[a].jmlRencanaKontroldanRujukan + "] [persentase: " + data[a].persentase + "]"
                            })
                        }
                        $("#txt_bpjs_spri_poliKontrol").select2({
                            data: parsedData
                        });
                        refreshJadwalDokter();
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            });
        }

        function refreshJadwalDokter() {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=" + $("#txt_bpjs_spri_jenis").val() + "&kodepoli=" + $("#txt_bpjs_spri_poliKontrol option:selected").val() + "&tglrencanakontrol=" + $("#txt_bpjs_spri_tglRencanaKontrol").val(),
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
                    $("#txt_bpjs_spri_kodeDokter option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_spri_kodeDokter").select2({
                            "language": {
                                "noResults": function() {
                                    return response.metadata.message;
                                }
                            }
                        });
                        $("#txt_bpjs_spri_kodeDokter").trigger("change.select2");
                    } else {
                        var data = response.response;
                        // console.log(data);
                        var parsedData = [];
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodeDokter,
                                text: "[Dokter: " + data[a].kodeDokter + " - " + data[a].namaDokter + "] [Jadwal Praktek: " + data[a].jadwalPraktek + "] [Kapasitas: " + data[a].kapasitas + "]"
                            })
                        }
                        $("#txt_bpjs_spri_kodeDokter").select2({
                            data: parsedData
                        });
                        console.log(parsedData);
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            });
        }

        if ($("#txt_bpjs_spri_jenis").val() == 1) {
            $("#col_spri_noKartu").show();
            $("#col_spri_noSep").hide();
        } else {
            $("#col_spri_noSep").show();
            $("#col_spri_noKartu").hide();
        }

        $("#txt_bpjs_spri_jenis").select2().on("select2:select", function(e) {
            if ($("#txt_bpjs_spri_jenis").val() == 1) {

                $("#col_spri_noKartu").show();
                $("#col_spri_noSep").hide();

                $("#txt_bpjs_spri_noSep option").remove();

                $("#txt_nama_peserta").val('');
                $("#txt_tgllahir_peserta").val('');
                $("#panel-peserta").hide();
            } else {

                $("#col_spri_noSep").show();
                $("#col_spri_noKartu").hide();

                $("#txt_bpjs_spri_noKartu option").remove();

                $("#txt_nama_peserta").val('');
                $("#txt_tgllahir_peserta").val('');
                $("#panel-peserta").hide();
            }
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_noKartu").on("change", function() {
            refreshSpesialistik($("#txt_bpjs_spri_noKartu").val());
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_noSep").on("change", function() {
            refreshSpesialistik($("#txt_bpjs_spri_noSep").val());
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_poliKontrol").on("select2:select", function(e) {
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_tglRencanaKontrol").on("change", function() {
            if ($("#txt_bpjs_spri_jenis").val() == 1) {
                refreshSpesialistik($("#txt_bpjs_spri_noKartu").val());
            } else {
                refreshSpesialistik($("#txt_bpjs_spri_noSep").val());
            }
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_tglRencanaKontrol").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_spri_poliKontrol").select2().addClass("form-control");
        $("#txt_bpjs_spri_kodeDokter").select2().addClass("form-control");

        $("#btnTambahSPRI1").click(function() {
            $("#modal-spri").modal("show");
            $("#txt_bpjs_rk_pasien").removeAttr("disabled");
            $("#txt_bpjs_rk_sep").removeAttr("disabled");
            $("#txt_bpjs_rk_nomor_kartu").focus();
            MODE = "ADD";
        });

        $("#btnTambahSPRI2").click(function() {
            $("#modal-spri").modal("show");
            $("#txt_bpjs_rk_pasien").removeAttr("disabled");
            $("#txt_bpjs_rk_sep").removeAttr("disabled");
            $("#txt_bpjs_rk_nomor_kartu").focus();
            MODE = "ADD";
        });

        $("#btnProsesSPRI").click(function() {
            Swal.fire({
                title: "BPJS Rencana Kontrol/Inap",
                text: "Buat Rencana Kontrol/Inap baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (parseInt($("#txt_bpjs_spri_jenis").val()) === 1) {
                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "rc/sync.sh/insertrcspri",
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
                            data: JSON.stringify({
                                "noKartu": $("#txt_bpjs_spri_noKartu").val(),
                                "kodeDokter": $("#txt_bpjs_spri_kodeDokter").val(),
                                "poliKontrol": $("#txt_bpjs_spri_poliKontrol").val(),
                                "tglRencanaKontrol": $("#txt_bpjs_spri_tglRencanaKontrol").val(),
                                "user": __MY_NAME__
                            }),
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "SPRI",
                                        "SPRI berhasil diproses",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        ListRencanaKontrolInap.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal buat SPRI",
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire(
                                    "Gagal buat SPRI",
                                    response.responseJSON.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        });
                    } else {
                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "rc/sync.sh/insertrc",
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
                            data: JSON.stringify({
                                "noSEP": $("#txt_bpjs_spri_noSep").val(),
                                "kodeDokter": $("#txt_bpjs_spri_kodeDokter").val(),
                                "poliKontrol": $("#txt_bpjs_spri_poliKontrol").val(),
                                "tglRencanaKontrol": $("#txt_bpjs_spri_tglRencanaKontrol").val(),
                                "user": __MY_NAME__
                            }),
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "Rencana Kontrol",
                                        "Rencana Kontrol berhasil diproses",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        ListRencanaKontrolInap.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal buat Rencana Kontrol",
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire(
                                    "Gagal buat Rencana Kontrol",
                                    response.responseJSON.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        });
                    }
                }
            });
        });

        //EDIT ZONE
        $("body").on("click", "#btnTestEdit", function() {
            var data = {
                "noSuratKontrol": "0301R0010120K000003",
                "tglRencanaKontrol": "2020-01-21",
                "tglTerbit": "2020-01-21",
                "jnsKontrol": "2",
                "poliTujuan": "010",
                "namaPoliTujuan": "ENDOKRIN-METABOLIK-DIABETES",
                "kodeDokter": "266822",
                "namaDokter": "DR.dr.H Eva Decroli, SpPD K-EMD Finasim",
                "flagKontrol": "False",
                "kodeDokterPembuat": null,
                "namaDokterPembuat": null,
                "namaJnsKontrol": "Kontrol",
                "sep": {
                    "noSep": "0069R0350823V000014",
                    "tglSep": "2020-01-18",
                    "jnsPelayanan": "Rawat Jalan",
                    "poli": "010 - ENDOKRIN-METABOLIK-DIABETES",
                    "diagnosa": "E11 - Non-insulin-dependent diabetes mellitus",
                    "peserta": {
                        "noKartu": "0000015450401",
                        "nama": "PIASDIL",
                        "tglLahir": "1954-04-12",
                        "kelamin": "L",
                        "hakKelas": "-"
                    },
                    "provUmum": {
                        "kdProvider": "03030101",
                        "nmProvider": "TARUSAN"
                    },
                    "provPerujuk": {
                        "kdProviderPerujuk": "0042R007",
                        "nmProviderPerujuk": "Rumah Sakit BKM Painan",
                        "asalRujukan": "2",
                        "noRujukan": "0042R0070819B000072",
                        "tglRujukan": "2020-01-18"
                    }
                }
            };

            $('#txt_bpjs_edit_spri_noSuratKontrol').val(data.noSuratKontrol);

            $("#txt_bpjs_edit_spri_jenis option[value=\"" + data.jnsKontrol + "\"]").prop("selected", true);
            $("#txt_bpjs_edit_spri_jenis").prop("disabled", true);
            $("#txt_bpjs_edit_spri_jenis").trigger("change");

            if (data.jnsKontrol == 1) {
                $("#txt_bpjs_edit_spri_noKartu").append("<option value=\"" + data.sep.peserta.noKartu + "\">" + data.sep.peserta.noKartu + "</option>");
                $("#txt_bpjs_edit_spri_noKartu").select2("data", {
                    id: data.sep.peserta.noKartu,
                    text: data.sep.peserta.noKartu
                });
                $("#txt_bpjs_edit_spri_noKartu").trigger("change");
            } else {
                $("#txt_bpjs_edit_spri_noSep").append("<option value=\"" + data.sep.noSep + "\">" + data.sep.noSep + "</option>");
                $("#txt_bpjs_edit_spri_noSep").select2("data", {
                    id: data.sep.noSep,
                    text: data.sep.noSep
                });
                $("#txt_bpjs_edit_spri_noSep").prop("disabled", true);
                $("#txt_bpjs_edit_spri_noSep").trigger("change");
            }

            if (data.jnsKontrol == 1) {
                $("#col_edit_spri_noKartu").show();
                $("#col_edit_spri_noSep").hide();
            } else {
                $("#col_edit_spri_noSep").show();
                $("#col_edit_spri_noKartu").hide();
            }

            $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(data.tglRencanaKontrol);

            // refreshSpesialistikEdit();
            $("#txt_bpjs_edit_spri_poliKontrol").append("<option value=\"" + data.poliTujuan + "\">" + data.namaPoliTujuan + "</option>");
            $("#txt_bpjs_edit_spri_poliKontrol").select2("data", {
                id: data.poliTujuan,
                text: data.namaPoliTujuan
            });
            $("#txt_bpjs_edit_spri_poliKontrol").trigger("change");

            // refreshJadwalDokterEdit();
            $("#txt_bpjs_edit_spri_kodeDokter").append("<option value=\"" + data.kodeDokter + "\">" + data.namaDokter + "</option>");
            $("#txt_bpjs_edit_spri_kodeDokter").select2("data", {
                id: data.kodeDokter,
                text: data.namaDokter
            });
            $("#txt_bpjs_edit_spri_kodeDokter").trigger("change");

            $("#modal-edit-spri").modal("show");
        });

        $("body").on("click", ".btnEditSPRI", function() {
            var no_surat = $(this).attr("id");
            MODE = "EDIT";

            //Load Detail
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol/?nosuratkontrol=" + no_surat,
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

                    $('#txt_bpjs_edit_spri_noSuratKontrol').val(data.noSuratKontrol);

                    $("#txt_bpjs_edit_spri_jenis option[value=\"" + data.jnsKontrol + "\"]").prop("selected", true);
                    $("#txt_bpjs_edit_spri_jenis").prop("disabled", true);
                    $("#txt_bpjs_edit_spri_jenis").trigger("change");

                    if (data.jnsKontrol == 1) {
                        $("#txt_bpjs_edit_spri_noKartu").append("<option value=\"" + data.sep.peserta.noKartu + "\">" + data.sep.peserta.noKartu + "</option>");
                        $("#txt_bpjs_edit_spri_noKartu").select2("data", {
                            id: data.sep.peserta.noKartu,
                            text: data.sep.peserta.noKartu
                        });
                        $("#txt_bpjs_edit_spri_noKartu").trigger("change");
                    } else {
                        $("#txt_bpjs_edit_spri_noSep").append("<option value=\"" + data.sep.noSep + "\">" + data.sep.noSep + "</option>");
                        $("#txt_bpjs_edit_spri_noSep").select2("data", {
                            id: data.sep.noSep,
                            text: data.sep.noSep
                        });
                        $("#txt_bpjs_edit_spri_noSep").prop("disabled", true);
                        $("#txt_bpjs_edit_spri_noSep").trigger("change");
                    }

                    if (data.jnsKontrol == 1) {
                        $("#col_edit_spri_noKartu").show();
                        $("#col_edit_spri_noSep").hide();
                    } else {
                        $("#col_edit_spri_noSep").show();
                        $("#col_edit_spri_noKartu").hide();
                    }

                    $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(data.tglRencanaKontrol);

                    // refreshSpesialistikEdit();
                    $("#txt_bpjs_edit_spri_poliKontrol").append("<option value=\"" + data.poliTujuan + "\">" + data.namaPoliTujuan + "</option>");
                    $("#txt_bpjs_edit_spri_poliKontrol").select2("data", {
                        id: data.poliTujuan,
                        text: data.namaPoliTujuan
                    });
                    $("#txt_bpjs_edit_spri_poliKontrol").trigger("change");

                    // refreshJadwalDokterEdit();
                    $("#txt_bpjs_edit_spri_kodeDokter").append("<option value=\"" + data.kodeDokter + "\">" + data.namaDokter + "</option>");
                    $("#txt_bpjs_edit_spri_kodeDokter").select2("data", {
                        id: data.kodeDokter,
                        text: data.namaDokter
                    });
                    $("#txt_bpjs_edit_spri_kodeDokter").trigger("change");

                    $("#modal-edit-spri").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#panel-peserta-edit").hide();

        $("#txt_bpjs_edit_spri_noSep").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#col_edit_spri_noSep'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisep`,
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
                data: function(term) {
                    return {
                        nomorsep: term.term
                    };
                },
                cache: true,
                processResults: function(response) {
                    var data = [response.response];
                    return {
                        results: $.map(data, function() {
                            return {
                                text: data[0].noSep,
                                id: data[0].noSep,
                                noKartu: data[0].peserta.noKartu,
                                nama: data[0].peserta.nama,
                                tglLahir: data[0].peserta.tglLahir
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            console.log(data);
            $("#txt_nama_peserta_edit").val(data.nama + " - " + data.noKartu);
            $("#txt_tgllahir_peserta_edit").val(data.tglLahir);
            $("#panel-peserta-edit").fadeIn();

            refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noSep").val());
            refreshJadwalDokterEdit();
        });

        $("#txt_bpjs_edit_spri_noKartu").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No. Kartu tidak ditemukan";
                }
            },
            dropdownParent: $("#col_edit_spri_noKartu"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}peserta/sync.sh/getpesertabynokartu`,
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
                        nomorkartu: term.term,
                        tglsep: $("#txt_bpjs_edit_spri_tglRencanaKontrol").val()
                    };
                },
                processResults: function(response) {
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_edit_spri_noKartu").trigger("change.select2");
                    } else {
                        var data = response.response;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: data[0].noKartu,
                                    id: data[0].noKartu,
                                    nama: data[0].nama,
                                    nik: data[0].nik,
                                    tglLahir: data[0].tglLahir
                                }
                            })
                        };
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_peserta_edit").val(data.nama + " - " + data.nik);
            $("#txt_tgllahir_peserta_edit").val(data.tglLahir);
            $("#panel-peserta-edit").fadeIn();

            refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noKartu").val());
            refreshJadwalDokterEdit();
        });

        $("#txt_bpjs_edit_spri_poliKontrol").select2({
            dropdownParent: $('#col_edit_spri_poliKontrol')
        });

        $("#txt_bpjs_edit_spri_kodeDokter").select2({
            dropdownParent: $('#col_edit_spri_kodeDokter')
        });

        function refreshSpesialistikEdit(nomor) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/listspesialistik/?jeniskontrol=" + $("#txt_bpjs_edit_spri_jenis").val() + "&nomor=" + nomor + "&tglrencanakontrol=" + $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(),
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
                    $("#txt_bpjs_edit_spri_poliKontrol option").remove();

                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_edit_spri_poliKontrol").select2({
                            "language": {
                                "noResults": function() {
                                    return response.metadata.message;
                                }
                            }
                        });
                        $("#txt_bpjs_edit_spri_poliKontrol").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodePoli,
                                text: "[Poli: " + data[a].kodePoli + " - " + data[a].namaPoli + "] [Kapasitas: " + data[a].kapasitas + "] [jlh.Rencana Kontrol & Rujukan: " + data[a].jmlRencanaKontroldanRujukan + "] [persentase: " + data[a].persentase + "]"
                            })
                        }
                        $("#txt_bpjs_edit_spri_poliKontrol").select2({
                            data: parsedData
                        });
                        refreshJadwalDokterEdit();
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            });
        }

        function refreshJadwalDokterEdit() {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=" + $("#txt_bpjs_edit_spri_jenis").val() + "&kodepoli=" + $("#txt_bpjs_edit_spri_poliKontrol option:selected").val() + "&tglrencanakontrol=" + $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(),
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
                    $("#txt_bpjs_edit_spri_kodeDokter option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_edit_spri_kodeDokter").select2({
                            "language": {
                                "noResults": function() {
                                    return response.metadata.message;
                                }
                            }
                        });
                        $("#txt_bpjs_edit_spri_kodeDokter").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = [];
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodeDokter,
                                text: "[Dokter: " + data[a].kodeDokter + " - " + data[a].namaDokter + "] [Jadwal Praktek: " + data[a].jadwalPraktek + "] [Kapasitas: " + data[a].kapasitas + "]"
                            })
                        }
                        $("#txt_bpjs_edit_spri_kodeDokter").select2({
                            data: parsedData
                        });
                        console.log(parsedData);
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            });
        }

        if ($("#txt_bpjs_edit_spri_jenis").val() == 1) {
            $("#col_edit_spri_noKartu").show();
            $("#col_edit_spri_noSep").hide();
        } else {
            $("#col_edit_spri_noSep").show();
            $("#col_edit_spri_noKartu").hide();
        }

        // $("#txt_bpjs_edit_spri_jenis").select2().on("select2:select", function(e) {
        //     if ($("#txt_bpjs_edit_spri_jenis").val() == 1) {

        //         $("#col_edit_spri_noKartu").show();
        //         $("#col_edit_spri_noSep").hide();

        //         $("#txt_bpjs_edit_spri_noSep option").remove();

        //         $("#txt_nama_peserta_edit").val('');
        //         $("#txt_tgllahir_peserta_edit").val('');
        //         $("#panel-peserta-edit").hide();
        //     } else {

        //         $("#col_edit_spri_noSep").show();
        //         $("#col_edit_spri_noKartu").hide();

        //         $("#txt_bpjs_edit_spri_noKartu option").remove();

        //         $("#txt_nama_peserta_edit").val('');
        //         $("#txt_tgllahir_peserta_edit").val('');
        //         $("#panel-peserta-edit").hide();
        //     }
        //     refreshSpesialistikEdit();
        //     refreshJadwalDokterEdit();
        // });

        // $("#txt_bpjs_edit_spri_noKartu").on("change", function() {
        //     refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noKartu").val());
        //     refreshJadwalDokterEdit();
        // });

        // $("#txt_bpjs_edit_spri_noSep").on("change", function() {
        //     refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noSep").val());
        //     refreshJadwalDokterEdit();
        // });

        $("#txt_bpjs_edit_spri_poliKontrol").on("select2:select", function(e) {
            refreshJadwalDokterEdit();
        });

        $("#txt_bpjs_edit_spri_tglRencanaKontrol").on("change", function() {
            if ($("#txt_bpjs_edit_spri_jenis").val() == 1) {
                refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noKartu").val());
            } else {
                refreshSpesialistikEdit($("#txt_bpjs_edit_spri_noSep").val());
            }
            refreshJadwalDokterEdit();
        });

        $("#txt_bpjs_edit_spri_tglRencanaKontrol").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_edit_spri_poliKontrol").select2().addClass("form-control");
        $("#txt_bpjs_edit_spri_kodeDokter").select2().addClass("form-control");

        $("#btnEditSPRI").click(function() {
            Swal.fire({
                title: "BPJS SPRI / Rencana Kontrol",
                text: "Buat SPRI / Rencana Kontrol edit?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    if (parseInt($("#txt_bpjs_edit_spri_jenis").val()) === 1) {
                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "rc/sync.sh/updatercspri",
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
                            data: JSON.stringify({
                                "noSPRI": $('#txt_bpjs_edit_spri_noSuratKontrol').val(),
                                "kodeDokter": $("#txt_bpjs_edit_spri_kodeDokter").val(),
                                "poliKontrol": $("#txt_bpjs_edit_spri_poliKontrol").val(),
                                "tglRencanaKontrol": $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(),
                                "user": __MY_NAME__
                            }),
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "SPRI",
                                        "SPRI berhasil diedit",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        ListRencanaKontrolInap.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal edit SPRI",
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire(
                                    "Gagal edit SPRI",
                                    response.responseJSON.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        });
                    } else {
                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "rc/sync.sh/updaterc",
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
                            data: JSON.stringify({
                                "noSuratKontrol": $('#txt_bpjs_edit_spri_noSuratKontrol').val(),
                                "noSEP": $("#txt_bpjs_edit_spri_noSep").val(),
                                "kodeDokter": $("#txt_bpjs_edit_spri_kodeDokter").val(),
                                "poliKontrol": $("#txt_bpjs_edit_spri_poliKontrol").val(),
                                "tglRencanaKontrol": $("#txt_bpjs_edit_spri_tglRencanaKontrol").val(),
                                "user": __MY_NAME__
                            }),
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "Rencana Kontrol",
                                        "Rencana Kontrol berhasil diedit",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        ListRencanaKontrolInap.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal edit Rencana Kontrol",
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                console.log(response);
                                Swal.fire(
                                    "Gagal edit Rencana Kontrol",
                                    response.responseJSON.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                            }
                        });
                    }
                }
            });
        });
    });

    $("body").on("click", "#btnCetakRkTest", function() {
        var data = {
            "noSuratKontrol": "0301R0010120K000003",
            "tglRencanaKontrol": "2020-01-21",
            "tglTerbit": "2020-01-21",
            "jnsKontrol": "2",
            "poliTujuan": "010",
            "namaPoliTujuan": "ENDOKRIN-METABOLIK-DIABETES",
            "kodeDokter": "266822",
            "namaDokter": "DR.dr.H Eva Decroli, SpPD K-EMD Finasim",
            "flagKontrol": "False",
            "kodeDokterPembuat": null,
            "namaDokterPembuat": null,
            "namaJnsKontrol": "Kontrol",
            "sep": {
                "noSep": "0069R0350823V000014",
                "tglSep": "2020-01-18",
                "jnsPelayanan": "Rawat Jalan",
                "poli": "010 - ENDOKRIN-METABOLIK-DIABETES",
                "diagnosa": "E11 - Non-insulin-dependent diabetes mellitus",
                "peserta": {
                    "noKartu": "0000015450401",
                    "nama": "PIASDIL",
                    "tglLahir": "1954-04-12",
                    "kelamin": "L",
                    "hakKelas": "-"
                },
                "provUmum": {
                    "kdProvider": "03030101",
                    "nmProvider": "TARUSAN"
                },
                "provPerujuk": {
                    "kdProviderPerujuk": "0042R007",
                    "nmProviderPerujuk": "Rumah Sakit BKM Painan",
                    "asalRujukan": "2",
                    "noRujukan": "0042R0070819B000072",
                    "tglRujukan": "2020-01-18"
                }
            }
        };

        if (data.jnsKontrol == 1) {
            $("#title-surat").text("Surat Perintah Rawat Inap");
        } else {
            $("#title-surat").text("Surat Rencana Kontrol");

        }
        $("#rk_dokter").html(data.namaDokter + "<br>" + data.namaPoliTujuan);
        $("#rk_nomor_kartu").html(data.sep.peserta.noKartu);
        $("#rk_nama_pasien").html(data.sep.peserta.nama + " (" + data.sep.peserta.kelamin + ")");
        $("#rk_tanggal_lahir").html(data.sep.peserta.tglLahir);
        $("#rk_diagnosa_awal").html(data.sep.diagnosa);
        $("#rk_tglrencanakontrol").html(data.tglRencanaKontrol);

        var dateNow = new Date();
        var tgl_cetak = str_pad(2, dateNow.getDate()) + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
        $("#tgl_cetak_tgl_entri").html("Tgl. Entri " + data.tglTerbit + " | Tgl. Cetak " + tgl_cetak);

        $("#rk_nomor_surat").html(" <b class=\"text-info\">No. " + data.noSuratKontrol + "</b>");

        $("#modal-cetak-rk").modal("show");
    });

    $("body").on("click", ".btnPrintSPRI", function() {
        var no_surat = $(this).attr("id");
        $.ajax({
            async: false,
            url: __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol/?nosuratkontrol=" + no_surat,
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

                if (data.jnsKontrol == 1) {
                    $("#title-surat").text("Surat Perintah Rawat Inap");
                } else {
                    $("#title-surat").text("Surat Rencana Kontrol");

                }
                $("#rk_dokter").html(data.namaDokter + "<br>" + data.namaPoliTujuan);
                $("#rk_nomor_kartu").html(data.sep.peserta.noKartu);
                $("#rk_nama_pasien").html(data.sep.peserta.nama + " (" + data.sep.peserta.kelamin + ")");
                $("#rk_tanggal_lahir").html(data.sep.peserta.tglLahir);
                $("#rk_diagnosa_awal").html(data.sep.diagnosa);
                $("#rk_tglrencanakontrol").html(data.tglRencanaKontrol);

                var dateNow = new Date();
                var tgl_cetak = str_pad(2, dateNow.getDate()) + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
                $("#tgl_cetak_tgl_entri").html("Tgl. Entri " + data.tglTerbit + " | Tgl. Cetak " + tgl_cetak);

                $("#rk_nomor_surat").html(" <b class=\"text-info\">No. " + data.noSuratKontrol + "</b>");

                $("#modal-cetak-rk").modal("show");
            },
            error: function(response) {
                //
            }
        });
    });

    $("body").on("click", "#btnCetakRK", function() {
        $.ajax({
            async: false,
            url: __HOST__ + "miscellaneous/print_template/bpjs_rk.php",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "POST",
            data: {
                __PC_CUSTOMER__: __PC_CUSTOMER__,
                title_surat: $("#title-surat").text(),
                html_data_kiri: $("#data_rk_cetak_kiri").html(),
                html_data_kanan: $("#data_rk_cetak_kanan").html(),
                tgl_cetak_tgl_entri: $("#tgl_cetak_tgl_entri").html(),
            },
            success: function(response) {
                var containerItem = document.createElement("DIV");
                $(containerItem).html(response);
                $(containerItem).printThis({
                    importStyle: true,
                    importCSS: true,
                    base: true,
                    pageTitle: "Cetak Rencana Kontrol",
                    afterPrint: function() {
                        //
                    }
                });
            }
        });
    });
</script>

<div id="modal-spri" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> SPRI / Rencana Kontrol <code>Baru</code>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi SPRI / Rencana Kontrol</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Jenis Kontrol</label>
                                        <select class="form-control uppercase" id="txt_bpjs_spri_jenis">
                                            <option value="1">SPRI</option>
                                            <option value="2">Rencana Kontrol</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="col_spri_noKartu">
                                        <label for="">No. Kartu</label>
                                        <select id="txt_bpjs_spri_noKartu" class="form-control uppercase"></select>
                                    </div>
                                    <div class="form-group" id="col_spri_noSep">
                                        <label for="">No. Sep</label>
                                        <select id="txt_bpjs_spri_noSep" class="form-control uppercase"></select>
                                    </div>
                                    <div class="row" id="panel-peserta">
                                        <div class="col-8 form-group">
                                            <label for="">Peserta</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_peserta" readonly />
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="">Tgl. Lahir</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_peserta" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Rencana Kontrol</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_spri_tglRencanaKontrol" />
                                    </div>
                                    <div class="form-group" id="col_spri_poliKontrol">
                                        <label for="">Poli/Spesialistik</label>
                                        <select class="form-control uppercase" id="txt_bpjs_spri_poliKontrol"></select>
                                    </div>
                                    <div class="form-group" id="col_spri_kodeDokter">
                                        <label for="">Kode Dokter</label>
                                        <select class="form-control uppercase" id="txt_bpjs_spri_kodeDokter"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSPRI">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-edit-spri" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> SPRI / Rencana Kontrol <code>Edit</code>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi SPRI / Rencana Kontrol</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">No. Surat Kontrol/Inap</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_spri_noSuratKontrol" readonly />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Kontrol</label>
                                        <select class="form-control uppercase" id="txt_bpjs_edit_spri_jenis">
                                            <option value="1">SPRI</option>
                                            <option value="2">Rencana Kontrol</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="col_edit_spri_noKartu">
                                        <label for="">No. Kartu</label>
                                        <select id="txt_bpjs_edit_spri_noKartu" class="form-control uppercase"></select>
                                    </div>
                                    <div class="form-group" id="col_edit_spri_noSep">
                                        <label for="">No. Sep</label>
                                        <select id="txt_bpjs_edit_spri_noSep" class="form-control uppercase"></select>
                                    </div>
                                    <div class="row" id="panel-peserta-edit">
                                        <div class="col-8 form-group">
                                            <label for="">Peserta</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_peserta_edit" readonly />
                                        </div>
                                        <div class="col-4 form-group">
                                            <label for="">Tgl. Lahir</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_peserta_edit" readonly />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Rencana Kontrol</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_spri_tglRencanaKontrol" />
                                    </div>
                                    <div class="form-group" id="col_edit_spri_poliKontrol">
                                        <label for="">Poli/Spesialistik</label>
                                        <select class="form-control uppercase" id="txt_bpjs_edit_spri_poliKontrol"></select>
                                    </div>
                                    <div class="form-group" id="col_edit_spri_kodeDokter">
                                        <label for="">Kode Dokter</label>
                                        <select class="form-control uppercase" id="txt_bpjs_edit_spri_kodeDokter"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnEditSPRI">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-cetak-rk" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row col-lg-8 offset-sm-2">
                    <div class="col-md-5">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" />
                    </div>
                    <div>
                        <h5 class="modal-title" id="title-surat">
                        </h5>
                        <h5><?php echo __PC_CUSTOMER__; ?></h5>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 offset-sm-2">
                        <div id="data_rk_cetak_kiri">
                            <table class="table form-mode">
                                <tr>
                                    <td>Kepada Yth.</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_dokter"></td>
                                </tr>
                                <tr>
                                    <td colspan="3">Mohon Pemeriksaan dan Penanganan Lebih Lanjut:</td>
                                </tr>
                                <tr>
                                    <td>No. Kartu</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_nomor_kartu"></td>
                                </tr>
                                <tr>
                                    <td>Nama Pasien</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_nama_pasien"></td>
                                </tr>
                                <tr>
                                    <td>Tgl. Lahir</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_tanggal_lahir"></td>
                                </tr>
                                <tr>
                                    <td>Diagnosa Awal</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_diagnosa_awal"></td>
                                </tr>
                                <tr>
                                    <td>Rencana Kontrol</td>
                                    <td class="wrap_content">:</td>
                                    <td id="rk_tglrencanakontrol"></td>
                                </tr>
                            </table>
                        </div>
                        <table class="table form-mode">
                            <tr>
                                <td colspan="3" id="tgl_cetak_tgl_entri" style="padding-top: 50px;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_rk_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td id="rk_nomor_surat">No.Surat</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnCetakRK">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>