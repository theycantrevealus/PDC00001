<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {

        var clickedTab = [1];
        var selectedPropinsi = 0;
        var selectedKabupaten = 0;
        var DATAKUNJUNGAN, HISTORYPELAYANAN, DATAKLAIM, DATAKJASARAHARJA;
        var allowLoading = false;
        var SEARCH = "NO_SEARCH";

        var DetailDataKlaim = [];
        var DetailDataKlaimJasaraharja = [];

        $("#datakunjungan_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#datahistorypelayanan_text_search_tgl_mulai").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());
        $("#datahistorypelayanan_text_search_tgl_akhir").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#dataklaim_text_search_jns").select2();
        $("#dataklaim_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());
        $("#dataklaim_text_search_status").select2();

        $("#dataklaimjasaraharja_text_search_tgl_mulai").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#dataklaimjasaraharja_text_search_tgl_akhir").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrlDatakunjungan = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $('#datakunjungan_text_search_tgl').val() + "&jnspelayanan=" + $('#datakunjungan_text_search_jns').val();
        $("#datakunjungan_btn_search").click(function() {
            $('#alert-datakunjungan-container').fadeOut();
            getUrlDatakunjungan = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan?tglsep=" + $('#datakunjungan_text_search_tgl').val() + "&jnspelayanan=" + $('#datakunjungan_text_search_jns').val();
            SEARCH = "SEARCH_datakunjungan";
            DATAKUNJUNGAN.ajax.url(getUrlDatakunjungan).load();
        });
        $('#alert-datakunjungan-container').hide();

        var getUrlDataHistoryPelayanan = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datahistorypelayanan?nokartu=0002083184032&tglmulai=" + $('#datahistorypelayanan_text_search_tgl_mulai').val() + "&tglakhir=" + $('#datahistorypelayanan_text_search_tgl_akhir').val();
        $("#datahistorypelayanan_btn_search").click(function() {
            $('#alert-datahistorypelayanan-container').fadeOut();
            getUrlDataHistoryPelayanan = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datahistorypelayanan?nokartu=" + $('#datahistorypelayanan_text_search_no_kartu').val() + "&tglmulai=" + $('#datahistorypelayanan_text_search_tgl_mulai').val() + "&tglakhir=" + $('#datahistorypelayanan_text_search_tgl_akhir').val();
            SEARCH = "SEARCH_HISTORYPELAYANAN";
            HISTORYPELAYANAN.ajax.url(getUrlDataHistoryPelayanan).load();
        });
        $('#alert-datahistorypelayanan-container').hide();

        var getUrlDataklaim = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaim?tglpulang=2023-01-01&jnspelayanan=2&statusklaim=1";

        $('#alert-dataklaim-container').hide();
        $("#dataklaim_btn_search").click(function() {
            $('#alert-dataklaim-container').fadeOut();
            getUrlDataklaim = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaim?tglpulang=" + $('#dataklaim_text_search_tgl').val() + "&jnspelayanan=" + $('#dataklaim_text_search_jns').val() + "&statusklaim=" + $('#dataklaim_text_search_status option:selected').val();
            SEARCH = "SEARCH_DATAKLAIM";
            DATAKLAIM.ajax.url(getUrlDataklaim).load();
        });

        var getUrlDataklaimjasaraharja = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaimjasaraharja?jnspelayanan=2&tglmulai=2023-08-01&tglakhir=2023-08-30";
        $('#alert-dataklaimjasaraharja-container').hide();
        $("#dataklaimjasaraharja_btn_search").click(function() {
            $('#alert-dataklaimjasaraharja-container').fadeOut();
            getUrlDataklaimjasaraharja = __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaimjasaraharja?jnspelayanan=" + $('#dataklaimjasaraharja_text_search_jns').val() + "&tglmulai=" + $('#dataklaimjasaraharja_text_search_tgl_mulai').val() + "&tglakhir=" + $('#dataklaimjasaraharja_text_search_tgl_akhir').val();

            SEARCH = "SEARCH_DATAKJASARAHARJA";
            DATAKJASARAHARJA.ajax.url(getUrlDataklaimjasaraharja).load();

        });


        //Init
        DATAKUNJUNGAN = $("#bpjs_table_datakunjungan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "GET",
            initComplete: function() {
                $("#bpjs_table_datakunjungan_filter input").unbind().bind("keyup", function(e) {
                    if (e.keyCode == 13) {
                        if (this.value.length > 2 || this.value.length == 0) {
                            DATAKUNJUNGAN.search(this.value).draw();
                        }
                    }

                    return;
                });
            },
            "ajax": {
                // async: false,
                url: getUrlDatakunjungan,
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
                        if (SEARCH === "SEARCH_datakunjungan") {
                            $('#alert-datakunjungan').text(response.metadata.message);
                            $('#alert-datakunjungan-container').fadeIn();
                        }
                        return [];

                    } else {
                        var data = response.response;

                        response.draw = parseInt(response.response.response_draw);
                        response.recordsTotal = response.response.recordsTotal;
                        response.recordsFiltered = response.response.recordsFiltered;
                        $('#alert-datakunjungan-container').fadeOut();

                        return data;
                    }
                },
                error: function(response) {
                    console.clear();
                    console.log(response);
                    return [];
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [
                [10, 50, -1],
                [10, 50, "All"]
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
                    DATAKUNJUNGAN.ajax.reload();
                }
            } else if (child === 2) {
                if (clickedTab.indexOf(child) >= 0) {
                    HISTORYPELAYANAN.ajax.reload();
                } else {
                    clickedTab.push(2);
                    HISTORYPELAYANAN = $("#bpjs_table_datahistorypelayanan").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        initComplete: function() {
                            $("#bpjs_table_datahistorypelayanan_filter input").unbind().bind("keyup", function(e) {
                                if (e.keyCode == 13) {
                                    if (this.value.length > 2 || this.value.length == 0) {
                                        HISTORYPELAYANAN.search(this.value).draw();
                                    }
                                }

                                return;
                            });
                        },
                        "ajax": {
                            // async: false,
                            url: getUrlDataHistoryPelayanan,
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
                                    if (SEARCH === "SEARCH_HISTORYPELAYANAN") {
                                        $('#alert-datahistorypelayanan').text(response.metadata.message);
                                        $('#alert-datahistorypelayanan-container').fadeIn();
                                    }
                                    return [];
                                } else {
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;
                                    $('#alert-datahistorypelayanan-container').fadeOut();

                                    return data;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [10, 50, -1],
                            [10, 50, "All"]
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
                                    return row.namaPeserta;
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
                                    return (row.jnsPelayanan === "1") ? "Rawat Inap" : "Rawat Jalan";
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return (row.kelasRawat !== undefined && row.kelasRawat !== null) ? row.kelasRawat : "-";
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.poliTujSep + " - " + row.poli;
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
                                    return row.ppkPelayanan;
                                }
                            },
                        ]
                    });
                }
            } else if (child === 3) {
                if (clickedTab.indexOf(child) >= 0) {
                    DATAKLAIM.ajax.reload();
                } else {
                    clickedTab.push(3);
                    DATAKLAIM = $("#bpjs_table_dataklaim").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        initComplete: function() {
                            $("#bpjs_table_dataklaim_filter input").unbind().bind("keyup", function(e) {
                                if (e.keyCode == 13) {
                                    if (this.value.length > 2 || this.value.length == 0) {
                                        DATAKLAIM.search(this.value).draw();
                                    }
                                }

                                return;
                            });
                        },
                        "ajax": {
                            // async: false,
                            url: getUrlDataklaim,
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
                                    if (SEARCH === "SEARCH_DATAKLAIM") {
                                        $('#alert-dataklaim').text(response.metadata.message);
                                        $('#alert-dataklaim-container').fadeIn();
                                    }
                                    return [];

                                } else {
                                    var data = response.response;
                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;
                                    $('#alert-dataklaim-container').fadeOut();

                                    DetailDataKlaim = data;
                                    return data;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [10, 50, -1],
                            [10, 50, "All"]
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
                                    return row.peserta.nama + " - " + row.peserta.noKartu;;
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
                                    return (row.kelasRawat !== undefined && row.kelasRawat !== null) ? "Kelas " + row.kelasRawat : "-";
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.Inacbg.kode + "<br>" + row.Inacbg.nama;
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
                                    return row.status;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-info btn-sm btn_detail_dataklaim\" index=\"" + DATAKLAIM.data().count() + "\"  noSep=\"" + row.noSEP + "\"><i class=\"fa fa-search\"></i> Detail </button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 4) {
                if (clickedTab.indexOf(child) >= 0) {
                    DATAKJASARAHARJA.ajax.reload();
                } else {
                    clickedTab.push(4);
                    DATAKJASARAHARJA = $("#bpjs_table_dataklaimjasaraharja").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        initComplete: function() {
                            $("#bpjs_table_dataklaimjasaraharja_filter input").unbind().bind("keyup", function(e) {
                                if (e.keyCode == 13) {
                                    if (this.value.length > 2 || this.value.length == 0) {
                                        DATAKJASARAHARJA.search(this.value).draw();
                                    }
                                }

                                return;
                            });
                        },
                        "ajax": {
                            // async: false,
                            url: getUrlDataklaimjasaraharja,
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
                                    if (SEARCH === "SEARCH_DATAKJASARAHARJA") {
                                        $('#alert-dataklaimjasaraharja').text(response.metadata.message);
                                        $('#alert-dataklaimjasaraharja-container').fadeIn();
                                    }
                                    return [];
                                } else {
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;
                                    $('#alert-dataklaimjasaraharja-container').fadeOut();

                                    DetailDataKlaimJasaraharja = data;
                                    return data;
                                }
                            }
                        },
                        autoWidth: false,
                        "bInfo": false,
                        lengthMenu: [
                            [10, 50, -1],
                            [10, 50, "All"]
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
                                    return row.sep.noSEP;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.peserta.nama + " - " + row.sep.peserta.noKartu;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.tglSEP;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.tglPlgSEP;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.poli;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.sep.diagnosa;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.jasaRaharja.noRegister;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.jasaRaharja.tglKejadian;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-info btn-sm btn_detail_dataklaimjasaraharja\" index=\"" + DATAKJASARAHARJA.data().count() + "\"  noSep=\"" + row.noSep + "\"><i class=\"fa fa-search\"></i> Detail </button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            }

        });

        $("body").on("click", ".btn_detail_dataklaim", function() {
            var index = $(this).attr("index");
            var indexData = parseInt(index) - 1;
            var data = DetailDataKlaim[indexData];

            $("#detail_dataklaim_nosep").html(data.noSEP);
            $("#detail_dataklaim_tglsep").html(data.tglSep);
            $("#detail_dataklaim_tglplg").html(data.tglPulang);
            $("#detail_dataklaim_nama").html(data.peserta.nama);
            $("#detail_dataklaim_nokartu").html(data.peserta.noKartu);
            $("#detail_dataklaim_nomr").html(data.peserta.noMR);
            $("#detail_dataklaim_kelasrawat").html(data.kelasRawat);
            $("#detail_dataklaim_poli").html(data.poli);
            $("#detail_dataklaim_noFPK").html(data.noFPK);
            $("#detail_dataklaim_inacbg").html(data.Inacbg.kode + " - " + data.Inacbg.nama);
            $("#detail_dataklaim_status").html(data.status);
            $("#detail_dataklaim_byPengajuan").html(data.biaya.byPengajuan);
            $("#detail_dataklaim_byTarifGruper").html(data.biaya.byTarifGruper);
            $("#detail_dataklaim_byTarifRS").html(data.biaya.byTarifRS);
            $("#detail_dataklaim_byTopup").html(data.biaya.byTopup);
            $("#detail_dataklaim_bySetujui").html(data.biaya.bySetujui);

            $("#modal-detail-dataklaim").modal("show");
        });

        $("body").on("click", ".btn_detail_dataklaimjasaraharja", function() {
            var index = $(this).attr("index");
            var indexData = parseInt(index) - 1;
            var data = DetailDataKlaimJasaraharja[indexData];

            $("#detail_dataklaimjasaraharja_nosep").html(data.sep.noSEP);
            $("#detail_dataklaimjasaraharja_tglsep").html(data.sep.tglSEP);
            $("#detail_dataklaimjasaraharja_tglplg").html(data.sep.tglPlgSEP);
            $("#detail_dataklaimjasaraharja_nama").html(data.sep.peserta.nama);
            $("#detail_dataklaimjasaraharja_nokartu").html(data.sep.peserta.noKartu);
            $("#detail_dataklaimjasaraharja_nomr").html(data.sep.peserta.noMR);
            $("#detail_dataklaimjasaraharja_poli").html(data.sep.poli);
            $("#detail_dataklaimjasaraharja_diagnosa").html(data.sep.diagnosa);
            $("#detail_dataklaimjasaraharja_jnsPelayanan").html((data.sep.jnsPelayanan === 1) ? 'Rawat Inap' : 'Rawat Jalan');
            $("#detail_dataklaimjasaraharja_noRegister").html(data.jasaRaharja.noRegister);
            $("#detail_dataklaimjasaraharja_tglKejadian").html(data.jasaRaharja.tglKejadian);
            $("#detail_dataklaimjasaraharja_ketStatusDijamin").html(data.jasaRaharja.ketStatusDijamin);
            $("#detail_dataklaimjasaraharja_ketStatusDikirim").html(data.jasaRaharja.ketStatusDikirim);
            $("#detail_dataklaimjasaraharja_biayaDijamin").html(data.jasaRaharja.biayaDijamin);
            $("#detail_dataklaimjasaraharja_plafon").html(data.jasaRaharja.plafon);
            $("#detail_dataklaimjasaraharja_jmlDibayar").html(data.jasaRaharja.jmlDibayar);
            $("#detail_dataklaimjasaraharja_resultsJasaRaharja").html(data.jasaRaharja.resultsJasaRaharja);

            $("#modal-detail-dataklaimjasaraharja").modal("show");
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
                // async: false,
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

    });
</script>

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

<div id="modal-detail-dataklaim" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail Data Klaim</span>
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
                                <td id="detail_dataklaim_nosep"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_tglsep"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Pulang</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_tglplg"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_nama"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_nokartu"></td>
                            </tr>
                            <tr>
                                <td>No. Mr</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_nomr"></td>
                            </tr>
                            <tr>
                                <td>Kelas Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_kelasrawat"></td>
                            </tr>
                            <tr>
                                <td>Poli</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_poli"></td>
                            </tr>
                            <tr>
                                <td>No. FPK</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_noFPK"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4">
                        <table class="table form-mode">
                            <tr>
                                <td>INACBG</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_inacbg"></td>
                            </tr>
                            <tr>
                                <td>Status Klaim</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_status"></td>
                            </tr>
                            <tr>
                                <td>Biaya Pengajuan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_byPengajuan"></td>
                            </tr>
                            <tr>
                                <td>Biaya Tarif Gruper</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_byTarifGruper"></td>
                            </tr>
                            <tr>
                                <td>Biaya Tarif RS</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_byTarifRS"></td>
                            </tr>
                            <tr>
                                <td>Biaya TopUp</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_byTopup"></td>
                            </tr>
                            <tr>
                                <td>Biaya Setujui</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaim_bySetujui"></td>
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

<div id="modal-detail-dataklaimjasaraharja" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail Data Klaim Jasa Raharja</span>
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
                                <td id="detail_dataklaimjasaraharja_nosep"></td>
                            </tr>
                            <tr>
                                <td>Tgl. SEP</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_tglsep"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Pulang</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_tglplg"></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_nama"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_nokartu"></td>
                            </tr>
                            <tr>
                                <td>No. Mr</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_nomr"></td>
                            </tr>
                            <tr>
                                <td>Poli</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_poli"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_diagnosa"></td>
                            </tr>
                            <tr>
                                <td>Jns.Pelayanan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_jnsPelayanan"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4">
                        <table class="table form-mode">
                            <tr>
                                <td>No.Register</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_noRegister"></td>
                            </tr>
                            <tr>
                                <td>Tgl.Kejadian</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_tglKejadian"></td>
                            </tr>
                            <tr>
                                <td>Ket.Status Dijamin</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_ketStatusDijamin"></td>
                            </tr>
                            <tr>
                                <td>Ket.Status Dikirim</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_ketStatusDikirim"></td>
                            </tr>
                            <tr>
                                <td>Biaya Dijamin</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_biayaDijamin"></td>
                            </tr>
                            <tr>
                                <td>Plafon</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_plafon"></td>
                            </tr>
                            <tr>
                                <td>jml.Dibayar</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_jmlDibayar"></td>
                            </tr>
                            <tr>
                                <td>results JasaRaharja</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_dataklaimjasaraharja_resultsJasaRaharja"></td>
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