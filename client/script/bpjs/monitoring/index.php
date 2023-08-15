<script type="text/javascript">
    $(function() {

        var clickedTab = [1];
        var selectedPropinsi = 0;
        var selectedKabupaten = 0;
        var DATAKUNJUNGAN, HISTORYPELAYANAN, DATAKLAIM, DATAKJASARAHARJA;
        var allowLoading = false;
        var SEARCH = "NO_SEARCH";

        var getParameterDatakunjungantglsep = "2023-08-17";
        var getParameterDatakunjunganjnspelayanan = "2";
        $('#alert-datakunjungan-container').hide();
        $("#datakunjungan_btn_search").click(function() {
            $('#alert-datakunjungan-container').fadeOut();
            getParameterDatakunjungantglsep = $('#datakunjungan_text_search_tgl').val();
            getParameterDatakunjunganjnspelayanan = $('#datakunjungan_text_search_jns option:selected').val();
            SEARCH = "SEARCH_datakunjungan";
            DATAKUNJUNGAN.ajax.reload();
        });

        var getParameterDatahistorypelayananNokartu = "0002083184032";
        var getParameterDatahistorypelayananTglmulai = "2023-08-01";
        var getParameterDatahistorypelayananTglakhir = "2023-08-01";
        $('#alert-datahistorypelayanan-container').hide();
        $("#datahistorypelayanan_btn_search").click(function() {
            $('#alert-datahistorypelayanan-container').fadeOut();
            getParameterDatahistorypelayananNokartu = $('#datahistorypelayanan_text_search_no_kartu').val();
            getParameterDatahistorypelayananTglmulai = $('#datahistorypelayanan_text_search_tgl_mulai').val();
            getParameterDatahistorypelayananTglakhir = $('#datahistorypelayanan_text_search_tgl_akhir').val();
            SEARCH = "SEARCH_HISTORYPELAYANAN";
            HISTORYPELAYANAN.ajax.reload();
        });

        var getParameterDataklaimTglpulang = "2023-01-01";
        var getParameterDataklaimJnspelayanan = "2";
        var getParameterDataklaimStatusklaim = "1";
        $('#alert-dataklaim-container').hide();
        $("#dataklaim_btn_search").click(function() {
            $('#alert-dataklaim-container').fadeOut();
            getParameterDataklaimTglpulang = $('#dataklaim_text_search_tgl').val();
            getParameterDataklaimJnspelayanan = $('#dataklaim_text_search_jns option:selected').val();
            getParameterDataklaimStatusklaim = $('#dataklaim_text_search_status option:selected').val();
            SEARCH = "SEARCH_DATAKLAIM";
            DATAKLAIM.ajax.reload();
        });



        var getParameterDataklaimjasaraharjaJns = "2";
        var getParameterDataklaimjasaraharjaTglmulai = "2023-08-01";
        var getParameterDataklaimjasaraharjaTglakhir = "2023-08-01";
        $('#alert-dataklaimjasaraharja-container').hide();
        $("#dataklaimjasaraharja_btn_search").click(function() {
            $('#alert-dataklaimjasaraharja-container').fadeOut();
            getParameterDataklaimjasaraharjaJns = $('#dataklaimjasaraharja_text_search_jns option:selected').val();
            getParameterDataklaimjasaraharjaTglmulai = $('#dataklaimjasaraharja_text_search_tgl_mulai').val();
            getParameterDataklaimjasaraharjaTglakhir = $('#dataklaimjasaraharja_text_search_tgl_akhir').val();

            SEARCH = "SEARCH_DATAKJASARAHARJA";
            DATAKJASARAHARJA.ajax.reload();
        });


        //Init
        DATAKUNJUNGAN = $("#bpjs_table_datakunjungan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            //searchDelay: 3000,
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
                async: false,
                url: __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datakunjungan",
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
                data: function(d) {
                    d.tglsep = getParameterDatakunjungantglsep;
                    d.jnspelayanan = getParameterDatakunjunganjnspelayanan;
                },
                dataSrc: function(response) {

                    allowLoading = true;
                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                    var data = response.response;
                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                        response.draw = parseInt(response.response.response_draw);
                        response.recordsTotal = response.response.recordsTotal;
                        response.recordsFiltered = response.response.recordsFiltered;
                        $('#alert-datakunjungan-container').fadeOut();

                        return data;
                    } else {
                        if (SEARCH === "SEARCH_datakunjungan") {
                            $('#alert-datakunjungan').text(response.metadata.message);
                            $('#alert-datakunjungan-container').fadeIn();
                        }
                        return [];
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
                        return (row.kelasRawat !== undefined && row.kelasRawat !== null) ? row.kelasRawat : "-";
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
            ]
        });

        $("#tab-referensi-bpjs .nav-link").click(function(e) {
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            if (allowLoading) {
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
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "monitoring/sync.sh/datahistorypelayanan",
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
                                data: function(d) {
                                    d.nokartu = getParameterDatahistorypelayananNokartu;
                                    d.tglmulai = getParameterDatahistorypelayananTglmulai;
                                    d.tglakhir = getParameterDatahistorypelayananTglakhir;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-datahistorypelayanan-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_HISTORYPELAYANAN") {
                                            $('#alert-datahistorypelayanan').text(response.metadata.message);
                                            $('#alert-datahistorypelayanan-container').fadeIn();
                                        }
                                        return [];
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
                                        console.log(row);
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
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaim?tglpulang=2023-01-01&jnspelayanan=2&statusklaim=1",
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
                                data: function(d) {
                                    d.tglpulang = getParameterDataklaimTglpulang;
                                    d.jnspelayanan = getParameterDataklaimJnspelayanan;
                                    d.statusklaim = getParameterDataklaimStatusklaim;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-dataklaim-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_DATAKLAIM") {
                                            $('#alert-dataklaim').text(response.metadata.message);
                                            $('#alert-dataklaim-container').fadeIn();
                                        }
                                        return [];
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
                                        return "T.Sep: " + row.tglSep + "<br>T.Plg :" + row.tglPulang;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return row.peserta.nama;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return row.peserta.noKartu;
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
                                        return row.status;
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
                                        return "byPengajuan: " + row.biaya.byPengajuan + "<br>byTarifGruper: " + row.biaya.byTarifGruper + "<br>byTarifRS: " + row.biaya.byTarifRS + "<br>byTopup: "
                                        row.biaya.byTopup;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return row.biaya.bySetujui;
                                    }
                                },
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
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "monitoring/sync.sh/dataklaimjasaraharja",
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
                                data: function(d) {
                                    d.jnspelayanan = getParameterDataklaimjasaraharjaJns;
                                    d.tglmulai = getParameterDataklaimjasaraharjaTglmulai;
                                    d.tglakhir = getParameterDataklaimjasaraharjaTglakhir;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-dataklaimjasaraharja-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_DATAKJASARAHARJA") {
                                            $('#alert-dataklaimjasaraharja').text(response.metadata.message);
                                            $('#alert-dataklaimjasaraharja-container').fadeIn();
                                        }
                                        return [];
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
                                        return "Nama: " + row.peserta.nama + "<br>No. Kartu :" + row.peserta.noKartu + "<br>No. Register :" + row.jasaRaharja.noRegister;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return "T.Sep: " + row.sep.tglSEP + "<br>T.Pulang :" + row.sep.tglPlgSEP + "<br>T.Kejadian :" + row.jasaRaharja.tglKejadian;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return "ketStatusDijamin: " + row.jasaRaharja.ketStatusDijamin + "<br>ketStatusDikirim: " + row.jasaRaharja.ketStatusDikirim;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return "biayaDijamin : " + row.jasaRaharja.biayaDijamin + "<br>plafon: " + row.jasaRaharja.plafon + "<br>jmlDibayar: " + row.jasaRaharja.jmlDibayar;
                                    }
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return row.jasaRaharja.resultsJasaRaharja;
                                    }
                                },
                            ]
                        });
                    }
                } else {

                }
            } else {
                return false;
            }
        });













        $("#datakunjungan_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());
        $("#datakunjungan_text_search_jns").select2();

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

    });
</script>