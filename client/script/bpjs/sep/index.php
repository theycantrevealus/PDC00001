<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var selectedSEP = "",
            selectedSEPNo = "";
        var selectedLakaPenjamin = [];
        var isRujukan;

        var dataSepMonitoring = [];

        var MODE = "ADD";

        var clickedTab = [1];
        var ListMonitoringSep, SepInduk, SepInternal, PersetujuanSep, ListUpdateTglPlg, SuplesiJasaRaharja, DataIndukKecelakaan, IntegrasiSepInacbg, ListFingerPrint, RandomQuestion;


        //MONITORING SEP
        $("#tgl_sep_dt_kunjungan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrl = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $("#tgl_sep_dt_kunjungan").val() + "&jnspelayanan=" + $("#jenis_pelayanan_dt_kunjungan").val();

        $("#btn_search_dt_kunjungan").click(function() {
            $('#alert-sep-dt-kunjungan-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $("#tgl_sep_dt_kunjungan").val() + "&jnspelayanan=" + $("#jenis_pelayanan_dt_kunjungan").val();
            MODE = "SEARCH_KUNJUNGAN";
            ListMonitoringSep.ajax.url(getUrl).load();

        });
        $('#alert-sep-dt-kunjungan-container').hide();

        //SEP INDUK
        var getUrlSepInduk = __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=0000000000000000000";

        $("#btn_search_SepInduk").click(function() {
            $('#alert-SepInduk-container').fadeOut();
            getUrlSepInduk = __BPJS_SERVICE_URL__ + "sep/sync.sh/carisep?nomorsep=" + $("#nosep_SepInduk").val();
            MODE = "SEARCH_SEPINDUK";
            SepInduk.ajax.url(getUrlSepInduk).load();
        });
        $('#alert-SepInduk-container').hide();

        //SEP INTERNAL
        var getUrlSepInternal = __BPJS_SERVICE_URL__ + "sep/sync.sh/datainternalsep?nosep=0000000000000000000";

        $("#btn_search_SepInternal").click(function() {
            $('#alert-SepInternal-container').fadeOut();
            getUrlSepInternal = __BPJS_SERVICE_URL__ + "sep/sync.sh/datainternalsep?nosep=" + $("#nosep_SepInternal").val();
            MODE = "SEARCH_SEPINTERNAL";
            SepInternal.ajax.url(getUrlSepInternal).load();
        });
        $('#alert-SepInternal-container').hide();

        //LIST DATA PERSETUJUAN SEP
        $("#tgl_PersetujuanSep").datepicker({
            changeMonth: true,
            changeYear: true,
            // showButtonPanel: true,
            dateFormat: 'MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlPersetujuanSep = __BPJS_SERVICE_URL__ + "sep/sync.sh/persetujuansep?bulan=1&tahun=2022";

        var parse_tgl_PersetujuanSep = new Date($("#tgl_PersetujuanSep").datepicker("getDate"));
        $("#btn_search_PersetujuanSep").click(function() {
            $('#alert-PersetujuanSep-container').fadeOut();
            getUrlPersetujuanSep = __BPJS_SERVICE_URL__ + "sep/sync.sh/persetujuansep?bulan=" + parse_tgl_PersetujuanSep.getMonth() + "&tahun=" + parse_tgl_PersetujuanSep.getFullYear();
            MODE = "SEARCH_PersetujuanSep";
            PersetujuanSep.ajax.url(getUrlPersetujuanSep).load();
        });
        $('#alert-PersetujuanSep-container').hide();

        //LIST DATA UPDATE TANGGAL PULANG SEP
        $("#tgl_ListUpdateTglPlg").datepicker({
            changeMonth: true,
            changeYear: true,
            // showButtonPanel: true,
            dateFormat: 'MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlListUpdateTglPlg = __BPJS_SERVICE_URL__ + "sep/sync.sh/listupdatetglplng?bulan=1&tahun=2022&filter=";
        var parse_tgl_ListUpdateTglPlg = new Date($("#tgl_ListUpdateTglPlg").datepicker("getDate"));
        $("#btn_search_ListUpdateTglPlg").click(function() {
            $('#alert-ListUpdateTglPlg-container').fadeOut();
            getUrlListUpdateTglPlg = __BPJS_SERVICE_URL__ + "sep/sync.sh/listupdatetglplng?bulan=" + parse_tgl_ListUpdateTglPlg.getMonth() + "&tahun=" + parse_tgl_ListUpdateTglPlg.getFullYear() + "&filter=";
            MODE = "SEARCH_ListUpdateTglPlg";
            ListUpdateTglPlg.ajax.url(getUrlListUpdateTglPlg).load();
        });
        $('#alert-ListUpdateTglPlg-container').hide();

        //SUPLESI JASA RAHARJA
        $("#tgl_SuplesiJasaRaharja").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlSuplesiJasaRaharja = __BPJS_SERVICE_URL__ + "sep/sync.sh/suplesijasaraharja?nokartu=0001105689835&tglpelayanan=2021-07-30";
        $("#btn_search_SuplesiJasaRaharja").click(function() {
            $('#alert-SuplesiJasaRaharja-container').fadeOut();
            getUrlSuplesiJasaRaharja = __BPJS_SERVICE_URL__ + "sep/sync.sh/suplesijasaraharja?nokartu=" + $('#nokartu_SuplesiJasaRaharja').val() + "&tglpelayanan=" + $('#tgl_SuplesiJasaRaharja').val();
            MODE = "SEARCH_SuplesiJasaRaharja";
            SuplesiJasaRaharja.ajax.url(getUrlSuplesiJasaRaharja).load();
        });
        $('#alert-SuplesiJasaRaharja-container').hide();

        //DATA INDUK KECELAKAAN
        var getUrlDataIndukKecelakaan = __BPJS_SERVICE_URL__ + "sep/sync.sh/dataindukkecelakaan?nokartu=0001105689835";
        $("#btn_search_DataIndukKecelakaan").click(function() {
            $('#alert-DataIndukKecelakaan-container').fadeOut();
            getUrlDataIndukKecelakaan = __BPJS_SERVICE_URL__ + "sep/sync.sh/dataindukkecelakaan?nokartu=" + $('#nokartu_DataIndukKecelakaan').val();
            MODE = "SEARCH_DataIndukKecelakaan";
            DataIndukKecelakaan.ajax.url(getUrlDataIndukKecelakaan).load();
        });
        $('#alert-DataIndukKecelakaan-container').hide();

        //IntegrasiSepInacbg
        var getUrlIntegrasiSepInacbg = __BPJS_SERVICE_URL__ + "sep/sync.sh/integrasisepinacbg?nosep=0069R0350823V000014";
        $("#btn_search_IntegrasiSepInacbg").click(function() {
            $('#alert-IntegrasiSepInacbg-container').fadeOut();
            getUrlIntegrasiSepInacbg = __BPJS_SERVICE_URL__ + "sep/sync.sh/integrasisepinacbg?nosep=" + $('#nosep_IntegrasiSepInacbg').val();
            MODE = "SEARCH_IntegrasiSepInacbg";
            IntegrasiSepInacbg.ajax.url(getUrlIntegrasiSepInacbg).load();
        });
        $('#alert-IntegrasiSepInacbg-container').hide();

        //ListFingerPrint
        $("#tgl_ListFingerPrint").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlListFingerPrint = __BPJS_SERVICE_URL__ + "sep/sync.sh/listfingerprintsep?tglpelayanan=2023-10-06";
        $("#btn_search_ListFingerPrint").click(function() {
            $('#alert-ListFingerPrint-container').fadeOut();
            getUrlListFingerPrint = __BPJS_SERVICE_URL__ + "sep/sync.sh/listfingerprintsep?tglpelayanan=" + $('#tgl_ListFingerPrint').val();
            MODE = "SEARCH_ListFingerPrint";
            ListFingerPrint.ajax.url(getUrlListFingerPrint).load();
        });
        $('#alert-ListFingerPrint-container').hide();

        //RandomQuestion
        $("#tgl_RandomQuestion").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlRandomQuestion = __BPJS_SERVICE_URL__ + "sep/sync.sh/randomquestionsep?nokartu=0002083184032&tglpelayanan=2023-10-07";
        $("#btn_search_RandomQuestion").click(function() {
            $('#alert-RandomQuestion-container').fadeOut();
            getUrlRandomQuestion = __BPJS_SERVICE_URL__ + "sep/sync.sh/randomquestionsep?nokartu=" + $('#nokartu_RandomQuestion').val() + "&tglpelayanan=" + $('#tgl_RandomQuestion').val();
            MODE = "SEARCH_RandomQuestion";
            RandomQuestion.ajax.url(getUrlRandomQuestion).load();
        });
        $('#alert-RandomQuestion-container').hide();

        //INIT
        ListMonitoringSep = $("#table-monitoring-sep").DataTable({
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
                        if (MODE === "SEARCH_KUNJUNGAN") {
                            $('#alert-sep-dt-kunjungan').text(response.metadata.message);
                            $('#alert-sep-dt-kunjungan-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-sep-dt-kunjungan-container').fadeOut();
                        dataSepMonitoring = response.response;
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
                        return row.nama
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noKartu
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (row.noRujukan) ? row.noRujukan : " - ";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (row.jnsPelayanan === "1") ? "Rawat Inap" : "Rawat Jalan";
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
                        return (row.tglPlgSep) ? row.tglPlgSep : " - ";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-warning btn-sm btn-cetak-sep\" title=\"Cetak SEP\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button class=\"btn btn-info btn-sm btn-edit-sep\" title=\"Edit SEP\" diagnosa=\"" + row.diagnosa + "\" poli=\"" + row.poli + "\" id = \"" + row.noSep + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSEP\" title=\"Hapus SEP\" id=\"" + row.noSep + "\"><i class=\"fa fa-trash\"></i> Hapus</button>" +
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
                    ListMonitoringSep.ajax.reload();
                }
            } else if (child === 2) {
                if (clickedTab.indexOf(child) >= 0) {
                    SepInduk.ajax.reload();
                } else {
                    clickedTab.push(2);
                    SepInduk = $("#table-SepInduk").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlSepInduk,
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
                                if (response.metadata.code !== 200) {
                                    if (MODE === "SEARCH_SEPINDUK") {
                                        $('#alert-SepInduk').text(response.metadata.message);
                                        $('#alert-SepInduk-container').fadeIn();
                                    }
                                    return [];
                                } else {
                                    $('#alert-SepInduk-container').fadeOut();
                                    return [response.response];
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.peserta.nama + " - " + row.peserta.noKartu;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return (row.noRujukan) ? row.noRujukan : "-";
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
                                    return row.tujuanKunj.nama
                                }
                            },

                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-warning btn-sm btn-cetak-sep\" title=\"Cetak SEP\" id=\"" + row.noSep + "\">" +
                                        "<i class=\"fa fa-print\"></i> Cetak" +
                                        "</button>" +
                                        "<button class=\"btn btn-info btn-sm btn-edit-sep\" title=\"Edit SEP\" diagnosa=\"\" poli=\"\" id=\"" + row.noSep + "\">" +
                                        "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                        "</button>" +
                                        "<button class=\"btn btn-danger btnHapusSEP\" title=\"Hapus SEP\" id=\"" + row.noSep + "\"><i class=\"fa fa-trash\"></i> Hapus</button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 3) {
                if (clickedTab.indexOf(child) >= 0) {
                    SepInternal.ajax.reload();
                } else {
                    clickedTab.push(3);
                    SepInternal = $("#table-SepInternal").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlSepInternal,
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
                                    if (MODE === "SEARCH_SEPINTERNAL") {
                                        $('#alert-SepInternal-container').fadeIn();
                                        $('#alert-SepInternal').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-SepInternal-container').fadeOut();
                                    return response.response.list;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.nosep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nosepref;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglsep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglrujukinternal;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nokapst;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nosurat;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nmpoliasal;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nmtujuanrujuk;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.diagppk;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-warning btn-sm btn_detail_sepinternal\" index=\"" + SepInternal.data().count() + "\"  noSep=\"" + row.noSep + "\"><i class=\"fa fa-search\"></i> Detail </button>" +
                                        "<button class=\"btn btn-danger bpjs_hapus_sepinternal\" noSep=\"" + row.noSep + "\"  noSurat=\"" + row.noSurat + "\" tglRujukanInternal=\"" + row.tglRujukanInternal + "\" kdPoliTuj=\"" + row.kdPoliTuj + "\" ><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 4) {
                if (clickedTab.indexOf(child) >= 0) {
                    PersetujuanSep.ajax.reload();
                } else {
                    clickedTab.push(4);
                    PersetujuanSep = $("#table-PersetujuanSep").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlPersetujuanSep,
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
                                    if (MODE === "SEARCH_PersetujuanSep") {
                                        $('#alert-PersetujuanSep-container').fadeIn();
                                        $('#alert-PersetujuanSep').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-PersetujuanSep-container').fadeOut();
                                    return response.response.list;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noKartu;
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
                                    return row.tglsep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.jnspelayanan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.persetujuan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.status;
                                }
                            }
                        ]
                    });
                }
            } else if (child === 5) {
                if (clickedTab.indexOf(child) >= 0) {
                    ListUpdateTglPlg.ajax.reload();
                } else {
                    clickedTab.push(5);
                    ListUpdateTglPlg = $("#table-ListUpdateTglPlg").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlListUpdateTglPlg,
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
                                    if (MODE === "SEARCH_ListUpdateTglPlg") {
                                        $('#alert-ListUpdateTglPlg-container').fadeIn();
                                        $('#alert-ListUpdateTglPlg').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-ListUpdateTglPlg-container').fadeOut();
                                    return response.response.list;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noSepUpdating;
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
                                    return row.tglSep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglPulang;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.status;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.ppkTujuan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-warning btn-sm btn_detail_ListUpdateTglPlg\" index=\"" + ListUpdateTglPlg.data().count() + "\"  noSep=\"" + row.noSep + "\" noSepUpdating=\"" + row.noSepUpdating + "\" jnsPelayanan=\"" + row.jnsPelayanan + "\" ppkTujuan=\"" + row.ppkTujuan + "\" noKartu=\"" + row.noKartu + "\" nama=\"" + row.nama + "\" tglSep=\"" + row.tglSep + "\" tglPulang=\"" + row.tglPulang + "\" status=\"" + row.status + "\" tglMeninggal=\"" + row.tglMeninggal + "\" noSurat=\"" + row.noSurat + "\" noSurat=\"" + row.noSurat + "\" keterangan=\"" + row.keterangan + "\" user=\"" + row.user + "\"><i class=\"fa fa-search\"></i> Detail </button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 6) {
                if (clickedTab.indexOf(child) >= 0) {
                    SuplesiJasaRaharja.ajax.reload();
                } else {
                    clickedTab.push(6);
                    SuplesiJasaRaharja = $("#table-SuplesiJasaRaharja").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlSuplesiJasaRaharja,
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
                                    if (MODE === "SEARCH_SuplesiJasaRaharja") {
                                        $('#alert-SuplesiJasaRaharja-container').fadeIn();
                                        $('#alert-SuplesiJasaRaharja').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-SuplesiJasaRaharja-container').fadeOut();
                                    return response.response.jaminan;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noRegister;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSepAwal;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSuratJaminan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglKejadian;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglSep;
                                }
                            }
                        ]
                    });

                    DataIndukKecelakaan = $("#table-DataIndukKecelakaan").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlDataIndukKecelakaan,
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
                                    if (MODE === "SEARCH_DataIndukKecelakaan") {
                                        $('#alert-DataIndukKecelakaan-container').fadeIn();
                                        $('#alert-DataIndukKecelakaan').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-DataIndukKecelakaan-container').fadeOut();
                                    return response.response.list;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noSEP;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglKejadian;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.ppkPelSEP;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kdProp;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kdKab;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kdKec;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.ketKejadian;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSEPSuplesi;
                                }
                            }
                        ]
                    });
                }
            } else if (child === 7) {
                if (clickedTab.indexOf(child) >= 0) {
                    IntegrasiSepInacbg.ajax.reload();
                } else {
                    clickedTab.push(7);
                    IntegrasiSepInacbg = $("#table-IntegrasiSepInacbg").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlIntegrasiSepInacbg,
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
                                    if (MODE === "SEARCH_IntegrasiSepInacbg") {
                                        $('#alert-IntegrasiSepInacbg-container').fadeIn();
                                        $('#alert-IntegrasiSepInacbg').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-IntegrasiSepInacbg-container').fadeOut();
                                    return [response.response.pesertasep];
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noRegister;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSep;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSepAwal;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSuratJaminan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglKejadian;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglSep;
                                }
                            }
                        ]
                    });
                }
            } else if (child === 8) {
                if (clickedTab.indexOf(child) >= 0) {
                    ListFingerPrint.ajax.reload();
                } else {
                    clickedTab.push(8);
                    ListFingerPrint = $("#table-ListFingerPrint").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlListFingerPrint,
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
                                    if (MODE === "SEARCH_ListFingerPrint") {
                                        $('#alert-ListFingerPrint-container').fadeIn();
                                        $('#alert-ListFingerPrint').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-ListFingerPrint-container').fadeOut();
                                    return response.response.list;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.noKartu;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.noSEP;
                                }
                            }
                        ]
                    });
                }
            } else if (child === 9) {
                if (clickedTab.indexOf(child) >= 0) {
                    RandomQuestion.ajax.reload();
                } else {
                    clickedTab.push(9);
                    RandomQuestion = $("#table-RandomQuestion").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrlRandomQuestion,
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
                                    if (MODE === "SEARCH_RandomQuestion") {
                                        $('#alert-RandomQuestion-container').fadeIn();
                                        $('#alert-RandomQuestion').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-RandomQuestion-container').fadeOut();
                                    return response.response.faskes;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [20, 50, -1],
                            [20, 50, "All"]
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
                                    return row.kode;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.nama;
                                }
                            }
                        ]
                    });
                }
            }
        });

        //ZONE GetFingerPrint
        $("body").on("click", "#btnGetFingerPrint", function() {
            $("#modal-finger-print").modal("show");
        });

        $("body").on("click", "#btnCariPasien", function() {
            // $("#nomor_peserta").html($('#txt_no_bpjs').val());
            var dateNow = new Date();
            var tglSekarang = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

            var btn_cari = $(this);
            btn_cari.html("Memuat...").removeClass("btn-success").addClass("btn-warning");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynokartu",
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
                data: {
                    nomorkartu: $('#txt_no_bpjs').val(),
                    tglsep: tglSekarang

                },
                success: function(response) {
                    $("#nomor_peserta").html('');
                    $("#nik_pasien").html('');
                    $("#nama_pasien").html('');
                    $("#tll_pasien").html('');
                    $("#usia_pasien").html('');
                    $("#kelamin_pasien").html('');
                    $("#jenis_pasien").html('');
                    $("#hakkelas_pasien").html('');
                    $("#status_bpjs").html('');

                    if (parseInt(response.metadata.code) !== 200) {
                        Swal.fire(
                            'BPJS Peserta ' + $('#txt_no_bpjs').val(),
                            response.metadata.message,
                            'warning'
                        ).then((result) => {
                            //
                        });
                    } else {
                        Swal.fire(
                            'BPJS Peserta ' + $('#txt_no_bpjs').val(),
                            'Ditemukan',
                            'success'
                        ).then((result) => {
                            //
                        });

                        var dataPeserta = response.response[0];
                        $("#nomor_peserta").html(dataPeserta.noKartu);
                        $("#nik_pasien").html(dataPeserta.nik);
                        $("#nama_pasien").html(dataPeserta.nama);
                        $("#tll_pasien").html(dataPeserta.tglLahir);
                        $("#usia_pasien").html(dataPeserta.umur.umurSekarang);
                        $("#kelamin_pasien").html(dataPeserta.sex);
                        $("#jenis_pasien").html(dataPeserta.jenispeserta.keterangan + " - " + dataPeserta.jenispeserta.kode);
                        $("#hakkelas_pasien").html(dataPeserta.hakkelas.kode + " - " + dataPeserta.hakkelas.keterangan);
                        $("#status_bpjs").html(dataPeserta.statuspeserta.keterangan);
                    }

                    btn_cari.html("<i class=\"fa fa-search\"></i>").removeClass("btn-warning").addClass("btn-success");
                },
                error: function(response) {
                    //
                }
            });
        });

        $("body").on("click", "#btnCekFingerPrint", function() {
            var dateNow = new Date();
            var tglSekarang = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

            var btn_cek = $(this);
            btn_cek.html("Memuat...").removeClass("btn-success").addClass("btn-warning");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "sep/sync.sh/fingerprintsepsep/sync.sh/fingerprintsep",
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
                data: {
                    nokartu: $('#txt_no_bpjs').val(),
                    tglpelayanan: tglSekarang
                },
                cache: true,
                success: function(response) {
                    if (parseInt(response.metadata.code) === 200) {
                        Swal.fire(
                            'BPJS Peserta ' + $('#txt_no_bpjs').val(),
                            response.response.status,
                            // 'success'
                        ).then((result) => {
                            //
                        });
                    } else {
                        Swal.fire(
                            'BPJS Peserta ' + $('#txt_no_bpjs').val(),
                            response.metadata.message,
                            'warning'
                        ).then((result) => {
                            //
                        });
                    }

                    btn_cek.html("<i class=\"fa fa-check\"></i> Cek Finger Print").removeClass("btn-warning").addClass("btn-success");
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                }
            });
        });

        //ZONE PENGAJUAN SEP
        $("body").on("click", "#btnPengajuanSep", function() {
            $("#modal-pengajuan-sep").modal("show");
        });

        $("#tglSep_pengajuansep").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#jnsPelayanan_pengajuansep").select2();
        $("#jnsPengajuan_pengajuansep").select2();

        $("#noKartu_pengajuansep").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.Kartu tidak ditemukan";
                }
            },
            dropdownParent: $("#col-nokartu-pengajuansep"),
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
                        tglsep: $('#tglSep_pengajuansep').val()
                    };
                },
                processResults: function(response) {
                    var data = response.response;
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: data[0].noKartu,
                                id: data[0].noKartu,
                                tglSep: data[0].tglSep,
                                nik: data[0].nik,
                                nama: data[0].nama,
                                tglLahir: data[0].tglLahir
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_pengajuansep").val(data.nama);
            $("#txt_nik_pengajuansep").val(data.nik);
            $("#txt_tgllahir_pengajuansep").val(data.tglLahir);
        });

        //ZONE APROVAL SEP
        $("body").on("click", "#btnAprovalPengajuanSep", function() {
            $("#modal-aproval-sep").modal("show");
        });

        $("#tglSep_aprovalsep").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#jnsPelayanan_aprovalsep").select2();

        $("#noKartu_aprovalsep").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.Kartu tidak ditemukan";
                }
            },
            dropdownParent: $("#col-nokartu-aprovalsep"),
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
                        tglsep: $('#tglSep_aprovalsep').val()
                    };
                },
                processResults: function(response) {
                    var data = response.response;
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: data[0].noKartu,
                                id: data[0].noKartu,
                                tglSep: data[0].tglSep,
                                nik: data[0].nik,
                                nama: data[0].nama,
                                tglLahir: data[0].tglLahir
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_aprovalsep").val(data.nama);
            $("#txt_nik_aprovalsep").val(data.nik);
            $("#txt_tgllahir_aprovalsep").val(data.tglLahir);
        });

        //ZONE UPDATE TANGGAL PULANG SEP
        $("body").on("click", ".btn_detail_ListUpdateTglPlg", function() {
            var index = $(this).attr("index");
            var noSep = $(this).attr("noSep");
            var noSepUpdating = $(this).attr("noSepUpdating");
            var jnsPelayanan = $(this).attr("jnsPelayanan");
            var ppkTujuan = $(this).attr("ppkTujuan");
            var noKartu = $(this).attr("noKartu");
            var nama = $(this).attr("nama");
            var tglSep = $(this).attr("tglSep");
            var tglPulang = $(this).attr("tglPulang");
            var status = $(this).attr("status");
            var tglMeninggal = $(this).attr("tglMeninggal");
            var noSurat = $(this).attr("noSurat");
            var keterangan = $(this).attr("keterangan");
            var user = $(this).attr("user");

            var btn_detail = $(this);
            btn_detail.html("Memuat...").removeClass("btn-info").addClass("btn-warning");

            $("#detail_dataupdatetglplg_noSep").html(nosep);
            $("#detail_dataupdatetglplg_noSepUpdating").html(noSepUpdating);
            $("#detail_dataupdatetglplg_nama").html(nama);
            $("#detail_dataupdatetglplg_noKartu").html(noKartu);
            $("#detail_dataupdatetglplg_tglSep").html(tglSep);
            $("#detail_dataupdatetglplg_jnsPelayanan").html(jnsPelayanan);
            $("#detail_dataupdatetglplg_ppkTujuan").html(ppkTujuan);
            $("#detail_dataupdatetglplg_tglPulang").html(tglPulang);
            $("#detail_dataupdatetglplg_tglMeninggal").html((tglMeninggal) ? tglMeninggal : "-");
            $("#detail_dataupdatetglplg_noSurat").html((noSurat) ? noSurat : "-");
            $("#detail_dataupdatetglplg_keterangan").html(keterangan);
            $("#detail_dataupdatetglplg_user").html(user);

            $("#modal-detail-dataupdatetglplg").modal("show");
            btn_detail.html("<i class=\"fa fa-search\"></i> Detail").removeClass("btn-info").addClass("btn-success");
        });

        $("body").on("click", "#btnUpdateTanggalPulangSep", function() {
            $("#modal-update-tanggal-plg-sep").modal("show");
        });

        $("#panel-meninggal").hide();
        $("#panel-KLL").hide();
        $("#statusPulang_updatetglplg").select2();

        $("#statusPulang_updatetglplg").change(function() {
            if (parseInt($("#statusPulang_updatetglplg option:selected").val()) !== 4) {
                $("#panel-meninggal").fadeOut();
                $("#tglMeninggal_updatetglplg").val('');
            } else {
                $("#panel-meninggal").fadeIn();
                $("#tglMeninggal_updatetglplg").datepicker({
                    dateFormat: "yy-mm-dd",
                    autoclose: true
                }).datepicker("setDate", new Date());
            }
        });

        $("#tglPulang_updatetglplg").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#noSep_updatetglplg").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#col-nosep-updatetglplg'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}sep/sync.sh/carisep`,
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
                                tglLahir: data[0].peserta.tglLahir,
                                kdStatusKecelakaan: data[0].kdStatusKecelakaan,
                                nmstatusKecelakaan: data[0].nmstatusKecelakaan
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_updatetglplg").val(data.nama);
            $("#txt_nokartu_updatetglplg").val(data.noKartu);
            $("#txt_tgllahir_updatetglplg").val(data.tglLahir);
            $("#txt_LakaLantas_updatetglplg").val(data.nmstatusKecelakaan);

            if (parseInt(data.kdStatusKecelakaan) > 0) {
                $("#panel-KLL").show();
            } else {
                $("#panel-KLL").hide();
            }
        });

        $("body").on("click", ".btn_detail_sepinternal", function() {
            var index = $(this).attr("index");
            var noSep = $(this).attr("noSep");

            var btn_detail = $(this);
            btn_detail.html("Memuat...").removeClass("btn-info").addClass("btn-warning");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "sep/sync.sh/datainternalsep?nosep=" + no_sep,
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
                    var index_data = index - 1;
                    var dataDetail = response.response.list[index_data];
                    $("#detail_sepinternal_nosep").html(dataDetail.nosep);
                    $("#detail_sepinternal_nosepref").html(dataDetail.nosepref);
                    $("#detail_sepinternal_tglsep").html(dataDetail.tglsep);
                    $("#detail_sepinternal_tglrujukinternal").html(dataDetail.tglrujukinternal);
                    $("#detail_sepinternal_nokapst").html(dataDetail.nokapst);
                    $("#detail_sepinternal_nosurat").html(dataDetail.nosurat);
                    $("#detail_sepinternal_poliasal").html(dataDetail.nmpoliasal);
                    $("#detail_sepinternal_kdpoliasal").html(dataDetail.kdpoliasal);
                    $("#detail_sepinternal_politujuan").html(dataDetail.nmtujuanrujuk);
                    $("#detail_sepinternal_dokter").html(dataDetail.kddokter + " - " + dataDetail.nmdokter);
                    $("#detail_sepinternal_diagnosa").html(dataDetail.diagppk + " - " + dataDetail.nmdiag);
                    $("#detail_sepinternal_ppkpelsep").html(dataDetail.ppkpelsep);
                    $("#detail_sepinternal_penunjang").html(dataDetail.kdpenunjang + " - " + dataDetail.nmpenunjang);
                    $("#detail_sepinternal_opsikonsul").html(dataDetail.opsikonsul);
                    $("#detail_sepinternal_flaginternal").html(dataDetail.flaginternal);
                    $("#detail_sepinternal_flagprosedur").html(dataDetail.flagprosedur);
                    $("#detail_sepinternal_flagsep").html(dataDetail.flagsep);
                    $("#detail_sepinternal_fuser").html(dataDetail.fuser);
                    $("#detail_sepinternal_fdate").html(dataDetail.fdate);

                    $("#modal-detail-sepinternal").modal("show");
                    btn_detail.html("<i class=\"fa fa-search\"></i> Detail").removeClass("btn-info").addClass("btn-success");
                },
                error: function(response) {
                    //
                }
            });
        });

        $("body").on("click", ".btn-cetak-sep", function() {
            var no_sep = $(this).attr("id");

            var btn_cetak_sep = $(this);
            btn_cetak_sep.html("Memuat ...").removeClass("btn-success").addClass("btn-warning");

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

                    $("#sep_nomor_kartu").html(dataSEP.peserta.noKartu + " <b class=\"text-info\">[No. Mr " + dataSEP.peserta.noMr + "]</b>");
                    $("#sep_nama_peserta").html(dataSEP.peserta.nama);
                    $("#sep_tanggal_lahir").html(dataSEP.peserta.tglLahir);
                    $("#sep_nomor_telepon").html('-');
                    $("#sep_spesialis").html(dataSEP.poli);
                    $("#sep_catatan").html(dataSEP.catatan);

                    $("#sep_peserta").html(dataSEP.peserta.jnsPeserta);
                    $("#sep_jenis_rawat").html(dataSEP.jnsPelayanan);
                    $("#sep_jenis_kunjungan").html(dataSEP.tujuanKunj.nama);
                    $("#sep_procedure").html(dataSEP.flagProcedure.nama);
                    $("#sep_kelas_hak").html(dataSEP.peserta.hakKelas);
                    $("#sep_kelas_rawat").html(dataSEP.kelasRawat);
                    $("#sep_penjamin").html(dataSEP.penjamin);

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rc/sync.sh/carisep/?nomorsep=" + no_sep,
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
                            var dataTambahan = response.response;
                            $("#sep_diagnosa_awal").html(dataTambahan.diagnosa);
                            $("#sep_perujuk").html(dataTambahan.provPerujuk.nmProviderPerujuk + " (" + dataTambahan.provPerujuk.kdProviderPerujuk + ")");
                        },
                        error: function(response) {
                            //
                        }
                    });

                    $("#modal-sep-cetak").modal("show");
                    btn_cetak_sep.html("<i class=\"fa fa-print\"></i> Cetak").removeClass("btn-warning").addClass("btn-success");
                },
                error: function(response) {
                    //
                }
            });
        });

        $("#btnCetakSEP").click(function() {
            var dateNow = new Date();
            var tgl_cetak = str_pad(2, dateNow.getDate()) + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();

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
                    tgl_cetak: tgl_cetak
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
                        data: JSON.stringify({
                            "request": {
                                "t_sep": {
                                    "noSep": no_sep,
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'SEP Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    ListMonitoringSep.ajax.reload();
                                    SepInduk.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    ListMonitoringSep.ajax.reload();
                                    SepInduk.ajax.reload();
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

        $("body").on("click", ".bpjs_hapus_sepinternal", function() {
            var noSep = $(this).attr("noSep");
            var noSurat = $(this).attr("noSurat");
            var tglRujukanInternal = $(this).attr("tglRujukanInternal");
            var kdPoliTuj = $(this).attr("kdPoliTuj");

            Swal.fire({
                title: "SEP Internal",
                title: "Hapus SEP Internal, No. SEP " + noSep + "?",
                showDenyButton: true,
                confirmButtonText: "Ya. Hapus",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/deleteinternalsep",
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
                            "request": {
                                "t_sep": {
                                    "noSep": noSep,
                                    "noSurat": noSurat,
                                    "tglRujukanInternal": tglRujukanInternal,
                                    "kdPoliTuj": kdPoliTuj,
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS SEP Internal',
                                    'SEP Internal Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    SepInternal.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS SEP Internal',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    SepInternal.ajax.reload();
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

        $("body").on("click", ".btn-edit-sep", function() {
            var no_sep = $(this).attr("id");
            var attr_diagnosa = "";
            var attr_poli = "";

            if ($(this).attr("diagnosa") !== null && $(this).attr("diagnosa") !== undefined && $(this).attr("diagnosa") !== "") {
                attr_diagnosa = $(this).attr("diagnosa");
            } else {
                attr_diagnosa = "";
            }
            if ($(this).attr("poli") !== null && $(this).attr("poli") !== undefined && $(this).attr("poli") !== "") {
                attr_poli = $(this).attr("poli");
            } else {
                attr_poli = "";
            }

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

                    $("#txt_bpjs_nosep").val(data.noSep);
                    $("#txt_bpjs_tgl_sep").val(data.tglSep);
                    $("#txt_bpjs_nama").val(data.peserta.nama);
                    $("#txt_bpjs_nomor").val(data.peserta.noKartu);
                    $("#txt_bpjs_rm").val(data.peserta.noMr);
                    $("#txt_bpjs_tgllahir").val(data.peserta.tglLahir);
                    $("#txt_bpjs_nomor_rujukan").val((data.noRujukan) ? data.noRujukan : "TIDAK DITEMUKAN");
                    $("#txt_bpjs_skdp").val((data.kontrol.noSurat) ? data.kontrol.noSurat : "TIDAK DITEMUKAN");
                    $("#txt_bpjs_internal_poli").text(attr_poli);

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "peserta/sync.sh/getpesertabynokartu",
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
                        data: {
                            nomorkartu: data.peserta.noKartu,
                            tglsep: data.tglSep,
                        },
                        success: function(response) {
                            var dataPeserta = response.response[0];
                            $("#txt_bpjs_nik").val(dataPeserta.nik);
                            $("#txt_bpjs_telepon").val(dataPeserta.mr.noTelepon);
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });

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

                    $("#txt_bpjs_poli_tujuan option").remove();
                    $("#txt_bpjs_poli_tujuan").append("<option>" + attr_poli + "</option>");
                    $("#txt_bpjs_poli_tujuan").select2("data", {
                        id: attr_poli,
                        text: attr_poli
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

                    $("#txt_bpjs_diagnosa_awal option").remove();
                    $("#txt_bpjs_diagnosa_awal").append("<option>" + attr_diagnosa + "</option>");
                    $("#txt_bpjs_diagnosa_awal").select2("data", {
                        id: attr_diagnosa,
                        text: attr_diagnosa
                    });
                    $("#txt_bpjs_diagnosa_awal").trigger("change");

                    $("#txt_bpjs_asesmen_pelayanan option[value=\"" + data.assestmenPel.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_asesmen_pelayanan").trigger("change");

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

                    $("#txt_bpjs_dpjp").select2({
                        dropdownParent: $("#group_dpjp")
                    });

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
                                if (response.metadata.code !== 200) {
                                    // $(target + " option").remove();
                                    return [];
                                } else {
                                    var data = response.response;

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
                                }

                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }

                    $("#txt_bpjs_dpjp").append("<option>" + data.dpjp.nmDPJP + "</option>");
                    $("#txt_bpjs_dpjp").select2("data", {
                        id: data.dpjp.kdDPJP,
                        text: data.dpjp.nmDPJP
                    });
                    $("#txt_bpjs_dpjp").trigger("change");


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
                    dataSetSEP = JSON.stringify({
                        "request": {
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
                        }
                    });

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
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    "Edit SEP Berhasil!",
                                    "SEP telah diedit",
                                    "success"
                                ).then((result) => {
                                    ListMonitoringSep.ajax.reload();
                                    SepInduk.ajax.reload();
                                    $("#modal-sep").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    "Gagal buat SEP",
                                    response.metadata.message,
                                    "warning"
                                ).then((result) => {
                                    ListMonitoringSep.ajax.reload();
                                    SepInduk.ajax.reload();
                                    $("#modal-sep").modal("hide");
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

        $("body").on("click", "#btnProsesPengajuanSep", function() {
            Swal.fire({
                title: "Proses Proses Pengajuan SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/pengajuansep",
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
                            "request": {
                                "t_sep": {
                                    "noKartu": $('#noKartu_pengajuansep').val(),
                                    "tglSep": $('#tglSep_pengajuansep').val(),
                                    "jnsPelayanan": $('#jnsPelayanan_pengajuansep').val(),
                                    "jnsPengajuan": $('#jnsPengajuan_pengajuansep').val(),
                                    "keterangan": $('#keterangan_pengajuansep').val(),
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Pengajuan SEP Berhasil',
                                    'success'
                                ).then((result) => {
                                    PersetujuanSep.ajax.reload();
                                    $("#modal-pengajuan-sep").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    PersetujuanSep.ajax.reload();
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                //
                            });
                            console.log(error);
                        }
                    });
                }
            });
        });

        $("body").on("click", "#btnProsesAprovalSep", function() {
            Swal.fire({
                title: "Proses Proses Aproval Pengajuan SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/approvalsep",
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
                            "request": {
                                "t_sep": {
                                    "noKartu": $('#noKartu_aprovalsep').val(),
                                    "tglSep": $('#tglSep_aprovalsep').val(),
                                    "jnsPelayanan": $('#jnsPelayanan_aprovalsep').val(),
                                    "keterangan": $('#keterangan_aprovalsep').val(),
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Aproval Pengajuan SEP Berhasil',
                                    'success'
                                ).then((result) => {
                                    PersetujuanSep.ajax.reload();
                                    $("#modal-aproval-sep").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    PersetujuanSep.ajax.reload();
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                //
                            });
                            console.log(error);
                        }
                    });
                }
            });
        });

        $("body").on("click", "#btnProsesUpdateTglPlg", function() {
            Swal.fire({
                title: "Proses Update Tanggal Pulang SEP?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "sep/sync.sh/updatetglplng",
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
                            "request": {
                                "t_sep": {
                                    "noSep": $('#noSep_updatetglplg').val(),
                                    "statusPulang": $('#statusPulang_updatetglplg').val(),
                                    "noSuratMeninggal": $('#noSuratMeninggal_updatetglplg').val(),
                                    "tglMeninggal": $('#tglMeninggal_updatetglplg').val(),
                                    "tglPulang": $('#tglPulang_updatetglplg').val(),
                                    "noLPManual": $('#noLPManual_updatetglplg').val(),
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Update Tanggal Pulang SEP Berhasil',
                                    'success'
                                ).then((result) => {
                                    ListUpdateTglPlg.ajax.reload();
                                    $("#modal-aproval-sep").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    ListUpdateTglPlg.ajax.reload();
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                //
                            });
                            console.log(error);
                        }
                    });
                }
            });
        });
    });
</script>

<div id="modal-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta <code>Edit</code>
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
                                    <div class="col-12 col-md-3 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 form-group">
                                        <label for="">Tanggal Lahir</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgllahir" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 form-group">
                                        <label for="">No. Telepon</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon">
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
                                            <label for="">Nomor SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nosep" readonly>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly>
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
                                                <option value="2">Rawat Jalan</option>
                                                <option value="1">Rawat Inap</option>
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
                                                    <option value="" selected disabled></option>
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
                                                    <option value="" selected disabled></option>
                                                    <option value="1">Pribadi</option>
                                                    <option value="2">Pemberi Kerja</option>
                                                    <option value="3">Asuransi Kesehatan Tambahan</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6" id="panel-rujukan">
                                        <div class="col-12 form-group">
                                            <label for="">Nomor Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" readonly>
                                        </div>
                                        <!-- <div id="panel_rujukan">
                                            <div class="col-12 form-group">
                                                <label for="">PPK Asal Rujukan</label>
                                                <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                            </div>
                                            <div class="col-12 mb-4 form-group">
                                                <label for="">Tanggal Rujukan</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan" readonly>
                                            </div>

                                            <div class="col-12 form-group">
                                                <div class="alert alert-info">
                                                    <div class="informasi_rujukan">
                                                        <table class="table form-mode">
                                                            <tr>
                                                                <td>Poli</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_poli"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Diagnosa</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_diagnosa"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Keluhan</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_keluhan"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hak Kelas</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_hak_kelas"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jenis Peserta</td>
                                                                <td>:</td>
                                                                <td id="txt_bpjs_rujuk_jenis_peserta"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
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
                                            <div class="col-12 mb-4 form-group">
                                                <label for="">No. Surat Kontrol/SKDP</label>
                                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_skdp" readonly />
                                            </div>
                                            <div class="col-12 mb-8 form-group">
                                                <div class="row">
                                                    <div class="col-6 form-group" id="group_spesialistik">
                                                        <label for="">Spesialistik DPJP</label>
                                                        <select class="form-control" id="txt_bpjs_dpjp_spesialistik"></select>
                                                    </div>
                                                    <div class="col-6 form-group" id="group_dpjp">
                                                        <label for="">DPJP</label>
                                                        <select class="form-control sep" id="txt_bpjs_dpjp"></select>
                                                    </div>
                                                </div>
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
                                            <div class="alert alert-info">
                                                <div class="col-12 col-md-8 mb-4 form-group">
                                                    <b for="">Poli Tujuan</b>
                                                    <blockquote style="padding-left: 25px;">
                                                        <h6 id="txt_bpjs_internal_poli"></h6>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Kerja</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_dk"></p>
                                                    </blockquote>
                                                </div>
                                                <div class="col-12 col-md-12 form-group">
                                                    <h6 for="">Diagnosa Banding</h6>
                                                    <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                    <blockquote style="padding-left: 25px;">
                                                        <p id="txt_bpjs_internal_db"></p>
                                                    </blockquote>
                                                </div>
                                            </div>
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
                                                <!-- <div class="col-12 mb-4 form-group">
                                                    <label for="">No. LP</label>
                                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_laka_no_lp">
                                                </div> -->
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
                                <td>Perujuk</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_perujuk"></td>
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
                                <td style="width: 120px;padding-bottom:10px;">Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_peserta"></td>
                            </tr>
                            <tr>
                                <td>Jns. Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_rawat"></td>
                            </tr>
                            <tr>
                                <td>Jns. Kunjungan</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_jenis_kunjungan"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="wrap_content">:</td>
                                <td id="sep_procedure"></td>
                            </tr>
                            <tr>
                                <td>Kls. Hak</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_hak"></td>
                            </tr>
                            <tr>
                                <td>Kls. Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_kelas_rawat"></td>
                            </tr>
                            <tr>
                                <td>Penjamin</td>
                                <td class="wrap_content">:</td>
                                <td id="sep_penjamin"></td>
                            </tr>
                        </table>
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

<div id="modal-detail-sepinternal" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail SEP Internal</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 offset-sm-3">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 150px;">No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_nosep"></td>
                            </tr>
                            <tr>
                                <td>No. SEP REF</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_nosepref"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_tglsep"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Rujuk Internal</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_tglrujukinternal"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_nokapst"></td>
                            </tr>
                            <tr>
                                <td>No. Surat</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_nosurat"></td>
                            </tr>
                            <tr>
                                <td>Poli Asal</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_poliasal"></td>
                            </tr>
                            <tr>
                                <td>Kode Poli Asal</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_kdpoliasal"></td>
                            </tr>
                            <tr>
                                <td>Poli Tujuan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_politujuan"></td>
                            </tr>
                            <tr>
                                <td>Dokter</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_dokter"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa PPK</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_diagnosa"></td>
                            </tr>
                            <tr>
                                <td>PPK Pelayanan SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_ppkpelsep"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4">
                        <table class="table form-mode">
                            <tr>
                                <td>Penunjang</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_penunjang"></td>
                            </tr>
                            <tr>
                                <td>Opsi Konsul</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_opsikonsul"></td>
                            </tr>
                            <tr>
                                <td>Flag Internal</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_flaginternal"></td>
                            </tr>
                            <tr>
                                <td>Flag Prosedur</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_flagprosedur"></td>
                            </tr>
                            <tr>
                                <td>Flag SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_flagsep"></td>
                            </tr>
                            <tr>
                                <td>FUSER</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_fuser"></td>
                            </tr>
                            <tr>
                                <td>FDATE</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_sepinternal_fdate"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-pengajuan-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Pengajuan SEP
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
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Peserta</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-3 form-group" id="col-nokartu-pengajuansep">
                                    <label for="">No. Kartu</label>
                                    <select id="noKartu_pengajuansep" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Nama</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_pengajuansep" readonly>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">NIK</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nik_pengajuansep" readonly>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Tgl. Lahir</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_pengajuansep" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pengajuan</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Tanggal SEP</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tglSep_pengajuansep" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Pelayanan</label>
                                        <select class="form-control uppercase" id="jnsPelayanan_pengajuansep">
                                            <option value="1">Rawat Inap</option>
                                            <option value="2">Rawar Jalan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Pengajuan</label>
                                        <select class="form-control uppercase" id="jnsPengajuan_pengajuansep">
                                            <option value="1">Pengajuan Backdate</option>
                                            <option value="2">Pengajuan Finger Print</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Keterangan</label>
                                        <textarea class="form-control" style="min-height: 200px;" id="keterangan_pengajuansep"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesPengajuanSep">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-aproval-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Aproval Pengajuan SEP
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
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Peserta</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-3 form-group" id="col-nokartu-aprovalsep">
                                    <label for="">No. Kartu</label>
                                    <select id="noKartu_aprovalsep" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Nama</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_aprovalsep" readonly>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">NIK</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nik_aprovalsep" readonly>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Tgl. Lahir</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_aprovalsep" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pengajuan</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Tanggal SEP</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tglSep_aprovalsep" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">Jenis Pelayanan</label>
                                        <select class="form-control uppercase" id="jnsPelayanan_aprovalsep">
                                            <option value="1">Rawat Inap</option>
                                            <option value="2">Rawar Jalan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Keterangan</label>
                                        <textarea class="form-control" style="min-height: 180px;" id="keterangan_aprovalsep"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesAprovalSep">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-update-tanggal-plg-sep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Update Tanggal Pulang SEP
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
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi SEP</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-3 form-group" id="col-nosep-updatetglplg">
                                    <label for="">No. SEP</label>
                                    <select id="noSep_updatetglplg" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Nama</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_updatetglplg" readonly>
                                </div>
                                <div class="col-2 form-group">
                                    <label for="">No. Kartu</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nokartu_updatetglplg" readonly>
                                </div>
                                <div class="col-2 form-group">
                                    <label for="">Tgl. Lahir</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_updatetglplg" readonly>
                                </div>
                                <div class="col-2 form-group">
                                    <label for="">Laka Lantas</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_LakaLantas_updatetglplg" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Tanggal Pulang</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Status Pulang</label>
                                        <select class="form-control uppercase" id="statusPulang_updatetglplg">
                                            <option value="1">Atas Persetujuan Dokter</option>
                                            <option value="3">Atas Permintaan Sendiri</option>
                                            <option value="4">Meninggal</option>
                                            <option value="5">Lain-lain</option>
                                        </select>
                                    </div>
                                    <div id="panel-meninggal">
                                        <div class="form-group">
                                            <label for="">No. Surat Meninggal</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="noSuratMeninggal_updatetglplg" />
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tanggal Meninggal</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="tglMeninggal_updatetglplg" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="panel-KLL">
                                        <label for="">No. LP Manual</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="noLPManual_updatetglplg" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Tanggal Pulang</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="tglPulang_updatetglplg" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesUpdateTglPlg">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-detail-dataupdatetglplg" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail Data Update Tanggal Pulang SEP</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 offset-sm-3">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 150px;">No. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_noSep"></td>
                            </tr>
                            <tr>
                                <td>No. SEP Updating</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_noSepUpdating"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_nama"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_noKartu"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_tglSep"></td>
                            </tr>
                            <tr>
                                <td>Jenis Pelayanan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_jnsPelayanan"></td>
                            </tr>
                            <tr>
                                <td>PPK Tujuan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_ppkTujuan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4">
                        <table class="table form-mode">
                            <tr>
                                <td>Tgl. Pulang</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_tglPulang"></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_status"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Meninggal</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_tglMeninggal"></td>
                            </tr>
                            <tr>
                                <td>Nomor Surat</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_noSurat"></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_keterangan"></td>
                            </tr>
                            <tr>
                                <td>User</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataupdatetglplg_user"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-finger-print" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pengecekan Finger Print Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <div class="col-md-12">
                        <div class="row">
                            <label for="txt_cari">Cek Peserta BPJS</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="search-form search-form--light input-group-lg col-md-10">
                                <input type="text" class="form-control" placeholder="No. BPJS" id="txt_no_bpjs" />
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success" id="btnCariPasien">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div class="col-lg-12">
                                <br />
                                <b class="text-warning">
                                    <i class="fa fa-info-circle"></i> Pastikan nomor sesuai dengan kartu BPJS. Jika tidak sesuai maka kemungkinan pasien yang dipilih salah. Input ulang nomor pada kartu untuk mengecek calon pasien.
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <table class="table table-striped" id="hasil_bpjs">
                        <tr>
                            <td width="120px">No. Peserta</td>
                            <td width="10px">:</td>
                            <td id="nomor_peserta"></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td id="nik_pasien"></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td id="nama_pasien"></td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>:</td>
                            <td id="tll_pasien"></td>
                        </tr>
                        <tr>
                            <td>Usia</td>
                            <td>:</td>
                            <td id="usia_pasien"></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>:</td>
                            <td id="kelamin_pasien"></td>
                        </tr>
                        <tr>
                            <td>Jenis Peserta</td>
                            <td>:</td>
                            <td id="jenis_pasien"></td>
                        </tr>
                        <tr>
                            <td>Hak Kelas</td>
                            <td>:</td>
                            <td id="hakkelas_pasien"></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
                            <td id="status_bpjs"></td>
                        </tr>
                    </table>
                </div>

            </div>
            <div class="modal-footer">

                <button class="btn btn-success" id="btnCekFingerPrint">
                    <i class="fa fa-check"></i> Cek Finger Print
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>