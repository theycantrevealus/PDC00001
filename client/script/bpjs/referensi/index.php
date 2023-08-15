<script type="text/javascript">
    $(function() {

        var clickedTab = [1];
        var selectedPropinsi = 0;
        var selectedKabupaten = 0;
        var DIAGNOSA, POLI, FASKES, DPJP, PROCEDURE, PROVINSI, KABUPATEN, KECAMATAN, DOKTER, SPESIALISTIK, RUANG_RAWAT, CARA_KELUAR, PASCA_PULANG;
        var allowLoading = false;
        var SEARCH = "NO_SEARCH";

        var getParameterDiagnosa = "0.100";
        $('#alert-diagnosa-container').hide();
        $("#diagnosa_btn_search").click(function() {
            getParameterDiagnosa = $('#diagnosa_text_search_kode').val();
            SEARCH = "SEARCH_DIAGNOSA";
            DIAGNOSA.ajax.reload();
        });

        var getParameterPoli = "0.100";
        $('#alert-poli-container').hide();
        $("#poli_btn_search").click(function() {
            getParameterPoli = $('#poli_text_search_kode').val();
            SEARCH = "SEARCH_POLI";
            POLI.ajax.reload();
        });

        var getParameterFaskesKode = "ppp";
        var getParameterFaskesJns = "2";
        $('#alert-fakses-container').hide();
        $("#faskes_btn_search").click(function() {
            getParameterFaskesKode = $('#faskes_text_search_kode').val();
            getParameterFaskesJns = $('#faskes_text_search_jns option:selected').val();
            SEARCH = "SEARCH_FASKES";
            FASKES.ajax.reload();
        });


        var getParameterDpjpJns = "2";
        var getParameterDpjpTgl = "2023-08-21";
        var getParameterDpjpSpesialis = "2";
        $('#alert-dpjp-container').hide();
        $("#dpjp_btn_search").click(function() {
            getParameterDpjpJns = $('#dpjp_text_search_jns option:selected').val();
            getParameterDpjpTgl = $('#dpjp_text_search_tgl').val();
            getParameterDpjpSpesialis = $('#dpjp_text_search_spesialis option:selected').val();

            SEARCH = "SEARCH_DPJP";
            DPJP.ajax.reload();
        });

        var getParameterProcedure = "0.100";
        $('#alert-procedure-container').hide();
        $("#procedure_btn_search").click(function() {
            getParameterProcedure = $('#procedure_text_search_kode').val();

            SEARCH = "SEARCH_PROCEDURE";
            PROCEDURE.ajax.reload();
        });

        var getParameterDokter = "pppp";
        $('#alert-dokter-container').hide();
        $("#dokter_btn_search").click(function() {
            getParameterDokter = $('#dokter_text_search_nama').val();

            SEARCH = "SEARCH_DOKTER";
            DOKTER.ajax.reload();
        });




        //Init
        DIAGNOSA = $("#bpjs_table_diagnosa").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            //searchDelay: 3000,
            serverMethod: "POST",
            initComplete: function() {
                $("#bpjs_table_diagnosa_filter input").unbind().bind("keyup", function(e) {
                    if (e.keyCode == 13) {
                        if (this.value.length > 2 || this.value.length == 0) {
                            DIAGNOSA.search(this.value).draw();
                        }
                    }

                    return;
                });
            },
            "ajax": {
                async: false,
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdiagnosa",
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
                data: function(d) {
                    d.kode = getParameterDiagnosa;
                },
                dataSrc: function(response) {

                    allowLoading = true;
                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                    var data = response.response;

                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                        response.draw = parseInt(response.response.response_draw);
                        response.recordsTotal = response.response.recordsTotal;
                        response.recordsFiltered = response.response.recordsFiltered;
                        $('#alert-diagnosa-container').fadeOut();

                        return data;
                    } else {
                        if (SEARCH === "SEARCH_DIAGNOSA") {
                            $('#alert-diagnosa').text(response.metadata.message);
                            $('#alert-diagnosa-container').fadeIn();
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

        $("#tab-referensi-bpjs .nav-link").click(function(e) {
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            if (allowLoading) {
                var child = $(this).get(0).hash.split("-");
                child = parseInt(child[child.length - 1]);
                if (child === 1) {
                    if (clickedTab.indexOf(child) >= 0) {
                        DIAGNOSA.ajax.reload();
                    }
                } else if (child === 2) {
                    if (clickedTab.indexOf(child) >= 0) {
                        POLI.ajax.reload();
                    } else {
                        clickedTab.push(2);
                        POLI = $("#bpjs_table_poli").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_poli_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            POLI.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getpoli",
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
                                    d.kode = getParameterPoli;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;
                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-poli-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_POLI") {
                                            $('#alert-poli').text(response.metadata.message);
                                            $('#alert-poli-container').fadeIn();
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
                } else if (child === 3) {
                    if (clickedTab.indexOf(child) >= 0) {
                        FASKES.ajax.reload();
                    } else {
                        clickedTab.push(3);
                        FASKES = $("#bpjs_table_fakses").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function() {
                                $("#bpjs_table_fakses_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            FASKES.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getfaskes",
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
                                data: function(d) {
                                    d.kode = getParameterFaskesKode;
                                    d.jns = getParameterFaskesJns;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {

                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-fakses-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_FASKES") {
                                            $('#alert-fakses').text(response.metadata.message);
                                            $('#alert-fakses-container').fadeIn();
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
                } else if (child === 4) {
                    if (clickedTab.indexOf(child) >= 0) {
                        DPJP.ajax.reload();
                    } else {
                        clickedTab.push(4);
                        DPJP = $("#bpjs_table_dpjp").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_dpjp_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            DPJP.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokter",
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
                                    d.jnspelayanan = getParameterDpjpJns;
                                    d.tglpelayanan = getParameterDpjpTgl;
                                    d.kode = getParameterDpjpSpesialis;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-dpjp-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_DPJP") {
                                            $('#alert-dpjp').text(response.metadata.message);
                                            $('#alert-dpjp-container').fadeIn();
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
                } else if (child === 5) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PROVINSI.ajax.reload();
                    } else {
                        clickedTab.push(5);
                        PROVINSI = $("#bpjs_table_provinsi").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_provinsi_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            PROVINSI.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getprov",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;

                                    return data;
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
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return "<button class=\"btn btn-info btn-sm btn_propinsi\" id=\"propinsi_" + row.kode + "\"><i class=\"fa fa-eye\"></i></button>";
                                    }
                                }
                            ]
                        });



                        KABUPATEN = $("#bpjs_table_kabupaten").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function() {
                                $("#bpjs_table_kabupaten_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            KABUPATEN.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getkab",
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
                                    d.kodeprov = selectedPropinsi;
                                },
                                dataSrc: function(response) {
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;

                                    return data;
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
                                },
                                {
                                    "data": null,
                                    render: function(data, type, row, meta) {
                                        return "<button class=\"btn btn-info btn-sm btn_kabupaten\" id=\"kabupaten_" + row.kode + "\"><i class=\"fa fa-eye\"></i></button>";
                                    }
                                }
                            ]
                        });

                        KECAMATAN = $("#bpjs_table_kecamatan").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function() {
                                $("#bpjs_table_kecamatan_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            KECAMATAN.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getkec",
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
                                    d.kodekab = selectedKabupaten;
                                },
                                dataSrc: function(response) {
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;

                                    return data;
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
                } else if (child === 6) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PROCEDURE.ajax.reload();
                    } else {
                        clickedTab.push(6);
                        PROCEDURE = $("#bpjs_table_procedure").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "POST",
                            initComplete: function() {
                                $("#bpjs_table_procedure_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            PROCEDURE.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getprocedure",
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
                                data: function(d) {
                                    d.kode = getParameterProcedure;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-procedure-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_PROCEDURE") {
                                            $('#alert-procedure').text(response.metadata.message);
                                            $('#alert-procedure-container').fadeIn();
                                        }
                                        return [];
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
                } else if (child === 7) {
                    if (clickedTab.indexOf(child) >= 0) {
                        KELAS_RAWAT.ajax.reload();
                    } else {
                        clickedTab.push(7);
                        KELAS_RAWAT = $("#bpjs_table_kelas_rawat").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_kelas_rawat_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            KELAS_RAWAT.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getkelasrawat",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    response.draw = parseInt(response.response.response_draw);
                                    response.recordsTotal = response.response.recordsTotal;
                                    response.recordsFiltered = response.response.recordsFiltered;

                                    return data;
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
                } else if (child === 8) {
                    if (clickedTab.indexOf(child) >= 0) {
                        DOKTER.ajax.reload();
                    } else {
                        clickedTab.push(8);
                        DOKTER = $("#bpjs_table_dokter").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_dokter_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            DOKTER.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokterbynama",
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
                                    d.namadpjp = getParameterDokter;
                                },
                                dataSrc: function(response) {
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;
                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        $('#alert-dokter-container').fadeOut();

                                        return data;
                                    } else {
                                        if (SEARCH === "SEARCH_DOKTER") {
                                            $('#alert-dokter').text(response.metadata.message);
                                            $('#alert-dokter-container').fadeIn();
                                        }
                                        return [];
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
                } else if (child === 9) {
                    if (clickedTab.indexOf(child) >= 0) {
                        SPESIALISTIK.ajax.reload();
                    } else {
                        clickedTab.push(9);
                        SPESIALISTIK = $("#bpjs_table_spesialistik").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_spesialistik_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            SPESIALISTIK.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getspesialis",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        return data;
                                    } else {
                                        return [];
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
                } else if (child === 10) {
                    if (clickedTab.indexOf(child) >= 0) {
                        RUANG_RAWAT.ajax.reload();
                    } else {
                        clickedTab.push(10);
                        RUANG_RAWAT = $("#bpjs_table_ruang_rawat").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_ruang_rawat_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            RUANG_RAWAT.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getruangrawat",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");

                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        return data;
                                    } else {
                                        return [];
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
                } else if (child === 11) {
                    if (clickedTab.indexOf(child) >= 0) {
                        CARA_KELUAR.ajax.reload();
                    } else {
                        clickedTab.push(11);
                        CARA_KELUAR = $("#bpjs_table_cara_keluar").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_cara_keluar_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            CARA_KELUAR.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getcarakeluar",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        return data;
                                    } else {
                                        return [];
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
                } else if (child === 12) {
                    if (clickedTab.indexOf(child) >= 0) {
                        PASCA_PULANG.ajax.reload();
                    } else {
                        clickedTab.push(12);
                        PASCA_PULANG = $("#bpjs_table_pasca_pulang").DataTable({
                            processing: true,
                            serverSide: true,
                            sPaginationType: "full_numbers",
                            bPaginate: true,
                            serverMethod: "GET",
                            initComplete: function() {
                                $("#bpjs_table_pasca_pulang_filter input").unbind().bind("keyup", function(e) {
                                    if (e.keyCode == 13) {
                                        if (this.value.length > 2 || this.value.length == 0) {
                                            PASCA_PULANG.search(this.value).draw();
                                        }
                                    }

                                    return;
                                });
                            },
                            "ajax": {
                                async: false,
                                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getpascapulang",
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
                                    allowLoading = true;
                                    $("#tab-referensi-bpjs .nav-link").removeClass("disabled");
                                    var data = response.response;

                                    if (response !== null && data !== null && response !== undefined && data !== undefined) {
                                        response.draw = parseInt(response.response.response_draw);
                                        response.recordsTotal = response.response.recordsTotal;
                                        response.recordsFiltered = response.response.recordsFiltered;
                                        return data;
                                    } else {
                                        return [];
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
                } else {

                }
            } else {
                return false;
            }
        });

















        // $("#bpjs_jenis_fakses").select2().on("select2:select", function(e) {
        //     $("#tab-referensi-bpjs .nav-link").addClass("disabled");
        //     FASKES.ajax.reload();
        // });

        // $("#range_dpjp").change(function() {
        //     if (
        //         !Array.isArray(getDateRange("#range_dpjp")[0]) &&
        //         !Array.isArray(getDateRange("#range_dpjp")[1])
        //     ) {
        //         $("#tab-referensi-bpjs .nav-link").addClass("disabled");
        //         DPJP.ajax.reload();
        //     }
        // });

        // $("#bpjs_jenis_fakses_dpjp").select2().on("select2:select", function(e) {
        //     $("#tab-referensi-bpjs .nav-link").addClass("disabled");
        //     DPJP.ajax.reload();
        // });


        $("#dpjp_text_search_tgl").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#dpjp_text_search_jns").select2();

        $("#dpjp_text_search_spesialis").select2({
            dropdownParent: $("#group_dpjp_text_search_spesialis"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getspesialis`,
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
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code === null) {
                        $("#dpjp_text_search_spesialis").trigger("change.select2");
                    } else {
                        var data = response.response;
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.kode + " - " + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("body").on("click", ".btn_propinsi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedPropinsi = id;
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            KABUPATEN.ajax.reload();
        });

        $("body").on("click", ".btn_kabupaten", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            selectedKabupaten = id;
            $("#tab-referensi-bpjs .nav-link").addClass("disabled");
            KECAMATAN.ajax.reload();
        });



    });
</script>