<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/pdfjs/pdf2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var MODE = "ADD";
        var clickedTab = [1];
        var RujukanList, RujukanKhususList, RujukanPesertaList, ListSpesialistikRujukan, ListSaranaRujukan;

        //LIST RUJUKAN PESERTA
        var getUrl_rujukanpeserta = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukanpesertapcare?nokartu=1231231234";
        var byNorujukan = "FALSE";

        $("#btn_search_rujukan_peserta_nokartu").click(function() {
            $('#alert-rujukanpeserta-container').fadeOut();

            if ($("#faskes_rujukan_peserta_nokartu").val() == 1) {
                getUrl_rujukanpeserta = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukanpesertapcare?nokartu=" + $('#nokartu_rujukan_peserta_nokartu').val();
            } else {
                getUrl_rujukanpeserta = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukanpesertarumahsakit?nokartu=" + $('#nokartu_rujukan_peserta_nokartu').val();
            }
            MODE = "SEARCH_RUJUKANPESERTA";
            byNorujukan = "FALSE";
            RujukanPesertaList.ajax.url(getUrl_rujukanpeserta).load();
        });

        $("#btn_search_rujukan_peserta_norujukan").click(function() {
            $('#alert-rujukanpeserta-container').fadeOut();

            if ($("#faskes_rujukan_peserta_norujukan").val() == 1) {
                getUrl_rujukanpeserta = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/carirujukanpcare?norujuk=" + $('#norujukan_faskes_rujukan_peserta_norujukan').val();
            } else {
                getUrl_rujukanpeserta = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/carirujukanrumahsakit?norujuk=" + $('#norujukan_faskes_rujukan_peserta_norujukan').val();
            }
            MODE = "SEARCH_RUJUKANPESERTA";
            byNorujukan = "TRUE";
            RujukanPesertaList.ajax.url(getUrl_rujukanpeserta).load();
        });
        $('#alert-rujukanpeserta-container').hide();


        //LIST RUJUKAN KELUAR RS
        $("#tglawal_listkeluarrujukan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#tglakhir_listkeluarrujukan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#btn_search_listkeluarrujukan").click(function() {
            $('#alert-rujukanlist-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listkeluarrujukan?tglmulai=" + $("#tglawal_listkeluarrujukan").val() + "&tglakhir=" + $("#tglakhir_listkeluarrujukan").val();
            MODE = "SEARCH_KELUARRUJUKAN";
            RujukanList.ajax.url(getUrl).load();
        });

        var getUrl = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listkeluarrujukan?tglmulai=2022-02-01&tglakhir=2022-03-01";
        var currentRujukan = "",
            currentRujukanText = "";
        var selectedBPJS = "",
            selectedPasien = "";
        $('#alert-rujukanlist-container').hide();


        // LIST RUJUKAN KHUSUS
        var getUrl_rujukankhusus = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukankhusus?bulan=4&tahun=2022";
        $("#tgl_rujukankhususlist").datepicker({
            changeMonth: true,
            changeYear: true,
            // showButtonPanel: true,
            dateFormat: 'MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

        var parse_tgl_rujukankhususlist = new Date($("#tgl_rujukankhususlist").datepicker("getDate"));
        $("#btn_search_rujukankhususlist").click(function() {
            $('#alert-rujukankhusus-container').fadeOut();
            getUrl_rujukankhusus = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listrujukankhusus?bulan=" + parse_tgl_rujukankhususlist.getMonth() + "&tahun=" + parse_tgl_rujukankhususlist.getFullYear();
            MODE = "SEARCH_RUJUKANKHUSUS";
            RujukanKhususList.ajax.url(getUrl_rujukankhusus).load();
        });
        $('#alert-rujukankhusus-container').hide();

        // LIST SPESIALISTIK RUJUKAN
        var getUrl_ListSpesialistikRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listspesialistikrujukan?kodeppk=00000000&tglrujuk=2023-10-04";

        $("#tglrujuk_ListSpesialistikRujukan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#btn_search_ListSpesialistikRujukan").click(function() {
            $('#alert-ListSpesialistikRujukan-container').fadeOut();
            getUrl_ListSpesialistikRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listspesialistikrujukan?kodeppk=" + $('#faskes_ListSpesialistikRujukan option:selected').val() + "&tglrujuk=" + $("#tglrujuk_ListSpesialistikRujukan").val();
            MODE = "SEARCH_SPESIALISTIKRUJUKAN";
            ListSpesialistikRujukan.ajax.url(getUrl_ListSpesialistikRujukan).load();
        });
        $('#alert-ListSpesialistikRujukan-container').hide();

        // LIST SARANA RUJUKAN
        var getUrl_ListSaranaRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listsaranarujukan?kodeppk=0000000";

        $("#btn_search_ListSaranaRujukan").click(function() {
            $('#alert-ListSaranaRujukan-container').fadeOut();
            getUrl_ListSaranaRujukan = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listsaranarujukan?kodeppk=" + $('#faskes_ListSaranaRujukan option:selected').val();
            MODE = "SEARCH_SARANARUJUKAN";
            ListSaranaRujukan.ajax.url(getUrl_ListSaranaRujukan).load();
        });
        $('#alert-ListSaranaRujukan-container').hide();

        //INIT DATATABLE
        RujukanPesertaList = $("#table-rujukan-peserta").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "GET",
            "ajax": {
                url: getUrl_rujukanpeserta,
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
                        if (MODE === "SEARCH_RUJUKANPESERTA") {
                            $('#alert-rujukanpeserta-container').fadeIn();
                            $('#alert-rujukanpeserta-list').text(response.metadata.message);
                        }

                        return [];
                    } else {
                        $('#alert-rujukanpeserta-container').fadeOut();
                        if (byNorujukan === "TRUE") {
                            return [response.response];
                        } else {
                            return response.response;
                        }
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
                        return row.noKunjungan;
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
                        return row.peserta.nik;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglKunjungan;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.provPerujuk.kode + " - " + row.provPerujuk.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.poliRujukan.kode + " - " + row.poliRujukan.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-detail-peserta\" title=\"Detail\" noKunjungan=\"" + row.noKunjungan + "\" diagnosa=\"" + row.diagnosa.kode + " - " + row.diagnosa.nama + "\" tglKunjungan=\"" + row.tglKunjungan + "\" pelayanan=\"" + row.pelayanan.nama + "\" provPerujuk=\"" + row.provPerujuk.kode + " - " + row.provPerujuk.nama + "\" poliRujukan=\"" + row.poliRujukan.kode + " - " + row.poliRujukan.nama + "\" keluhan=\"" + row.keluhan + "\" tglTAT=\"" + row.peserta.tglTAT + "\" statusPeserta=\"" + row.peserta.statusPeserta.keterangan + "\" provUmum=\"" + row.peserta.provUmum.kdProvider + " - " + row.peserta.provUmum.nmProvider + "\" umurSekarang=\"" + row.peserta.umur.umurSekarang + "\" umurSaatPelayanan=\"" + row.peserta.umur.umurSaatPelayanan + "\" noTelepon=\"" + row.peserta.mr.noTelepon + "\" noMR=\"" + row.peserta.mr.noMR + "\" sex=\"" + row.peserta.sex + "\" tglCetakKartu=\"" + row.peserta.tglCetakKartu + "\" tglTMT=\"" + row.peserta.tglTMT + "\" hakKelas=\"" + row.peserta.hakKelas.keterangan + "\" nik=\"" + row.peserta.nik + "\" tglLahir=\"" + row.peserta.tglLahir + "\" pisa=\"" + row.peserta.pisa + "\" nama=\"" + row.peserta.nama + "\" tglTATCOB=\"" + row.peserta.cob.tglTAT + "\" nmAsuransi=\"" + row.peserta.cob.nmAsuransi + "\" noAsuransi=\"" + row.peserta.cob.noAsuransi + "\" tglTMTCOB=\"" + row.peserta.cob.tglTMT + "\" noKartu=\"" + row.peserta.noKartu + "\" jenisPeserta=\"" + row.peserta.jenisPeserta.keterangan + "\" prolanisPRB=\"" + row.peserta.informasi.prolanisPRB + "\" eSEP=\"" + row.peserta.informasi.eSEP + "\" noSKTM=\"" + row.peserta.informasi.noSKTM + "\" dinsos=\"" + row.peserta.informasi.dinsos + "\"><i class=\"fa fa-search\"></i> Detail</button>" +
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
                    RujukanPesertaList.ajax.reload();
                }
            } else if (child === 2) {
                if (clickedTab.indexOf(child) >= 0) {
                    RujukanList.ajax.reload();
                } else {
                    clickedTab.push(2);
                    RujukanList = $("#table-rujukan").DataTable({
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
                                    if (MODE === "SEARCH_KELUARRUJUKAN") {
                                        $('#alert-rujukanlist').text(response.metadata.message);
                                        $('#alert-rujukanlist-container').fadeIn();
                                    }
                                    return [];
                                } else {
                                    $('#alert-rujukanlist-container').fadeOut();
                                    return response.response;
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
                                    return row.noRujukan;
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
                                    return row.tglRujukan;
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
                                    return (row.jnsPelayanan === "1") ? "Rawat Inap" : "Rawat Jalan";
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.ppkDirujuk + " - " + row.namaPpkDirujuk;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button class=\"btn btn-warning btn-sm bpjs_print_rujukan\" id=\"" + row.noRujukan + "\">" +
                                        "<i class=\"fa fa-print\"></i> Cetak" +
                                        "</button>" +
                                        "<button class=\"btn btn-info btn-sm bpjs_edit_rujukan\" id=\"" + row.noRujukan + "\">" +
                                        "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                                        "</button>" +
                                        "<button class=\"btn btn-danger bpjs_hapus_rujukan\" noRujukan=\"" + row.noRujukan + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 3) {
                if (clickedTab.indexOf(child) >= 0) {
                    RujukanKhususList.ajax.reload();
                } else {
                    clickedTab.push(3);

                    RujukanKhususList = $("#table-rujukan-khusus").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "GET",
                        "ajax": {
                            url: getUrl_rujukankhusus,
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
                                    if (MODE === "SEARCH_RUJUKANKHUSUS") {
                                        $('#alert-rujukankhusus-container').fadeIn();
                                        $('#alert-rujukankhusus-list').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-rujukankhusus-container').fadeOut();
                                    return response.response.rujukan;
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
                                    return row.idrujukan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.norujukan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglrujukan_awal;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.tglrujukan_berakhir;
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
                                    return row.nmpst;
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
                                        "<button class=\"btn btn-danger bpjs_hapus_rujukan_khusus\" noRujukan=\"" + row.norujukan + "\"  id=\"" + row.idrujukan + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                                        "</div>";
                                }
                            }
                        ]
                    });
                }
            } else if (child === 4) {
                if (clickedTab.indexOf(child) >= 0) {
                    ListSpesialistikRujukan.ajax.reload();
                } else {
                    clickedTab.push(4);

                    $("#jenis_faskes_ListSpesialistikRujukan").select2();
                    $("#faskes_ListSpesialistikRujukan").select2({
                        minimumInputLength: 2,
                        "language": {
                            "noResults": function() {
                                return "Faskes tidak ditemukan";
                            }
                        },
                        dropdownParent: $("#col_faskes_ListSpesialistikRujukan"),
                        ajax: {
                            url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
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
                                    jns: $("#jenis_faskes_ListSpesialistikRujukan option:selected").val(),
                                    kode: term.term
                                };
                            },
                            cache: true,
                            processResults: function(response) {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.kode + "-" + item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }).addClass("form-control").on("select2:select", function(e) {

                    });

                    ListSpesialistikRujukan = $("#table-ListSpesialistikRujukan").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "POST",
                        "ajax": {
                            url: getUrl_ListSpesialistikRujukan,
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
                            dataSrc: function(response) {
                                if (parseInt(response.metadata.code) !== 200) {
                                    if (MODE === "SEARCH_SPESIALISTIKRUJUKAN") {

                                        $('#alert-ListSpesialistikRujukan-container').fadeIn();
                                        $('#alert-ListSpesialistikRujukan').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-ListSpesialistikRujukan-container').fadeOut();
                                    return response.response;
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
                                    return row.namaSpesialis;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kodeSpesialis;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.kapasitas;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.jumlahRujukan;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.persentase;
                                }
                            }
                        ]
                    });
                }
            } else if (child === 5) {
                if (clickedTab.indexOf(child) >= 0) {
                    ListSaranaRujukan.ajax.reload();
                } else {
                    clickedTab.push(5);

                    $("#jenis_faskes_ListSaranaRujukan").select2();
                    $("#faskes_ListSaranaRujukan").select2({
                        minimumInputLength: 2,
                        "language": {
                            "noResults": function() {
                                return "Faskes tidak ditemukan";
                            }
                        },
                        dropdownParent: $("#col_faskes_ListSaranaRujukan"),
                        ajax: {
                            url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
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
                                    jns: $("#jenis_faskes_ListSaranaRujukan option:selected").val(),
                                    kode: term.term
                                };
                            },
                            cache: true,
                            processResults: function(response) {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.kode + "-" + item.nama,
                                            id: item.kode
                                        }
                                    })
                                };
                            }
                        }
                    }).addClass("form-control").on("select2:select", function(e) {

                    });

                    ListSaranaRujukan = $("#table-ListSaranaRujukan").DataTable({
                        processing: true,
                        serverSide: true,
                        sPaginationType: "full_numbers",
                        bPaginate: true,
                        serverMethod: "POST",
                        "ajax": {
                            url: getUrl_ListSaranaRujukan,
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
                            dataSrc: function(response) {
                                if (parseInt(response.metadata.code) !== 200) {
                                    if (MODE === "SEARCH_SARANARUJUKAN") {

                                        $('#alert-ListSaranaRujukan-container').fadeIn();
                                        $('#alert-ListSaranaRujukan').text(response.metadata.message);
                                    }
                                    return [];
                                } else {
                                    $('#alert-ListSaranaRujukan-container').fadeOut();
                                    return response.response;
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
                                    return row.kodeSarana;
                                }
                            },
                            {
                                "data": null,
                                render: function(data, type, row, meta) {
                                    return row.namaSarana;
                                }
                            }
                        ]
                    });
                }
            }
        });



        $("body").on("click", ".btn-detail-peserta", function() {
            var no_kunjungan = $(this).attr("noKunjungan");
            var diagnosa = $(this).attr("diagnosa");

            var DETAILButton = $(this);
            DETAILButton.html("Memuat Detail...").removeClass("btn-success").addClass("btn-warning");

            $("#nama_peserta").html($(this).attr("nama"));
            $("#nik").html($(this).attr("nik"));
            $("#no_kartu").html($(this).attr("noKartu"));
            $("#jenis_kelamin").html(($(this).attr("sex") === "L") ? "Laki-laki" : "Perempuan");
            $("#tgl_lahir").html($(this).attr("tglLahir"));
            $("#nomor_telepon").html($(this).attr("noTelepon"));
            $("#no_mr").html($(this).attr("noMR"));
            $("#status_peserta").html($(this).attr("statusPeserta"));
            $("#jenis_peserta").html($(this).attr("jenisPeserta"));
            $("#umur_saat_pelayanan").html($(this).attr("umurSaatPelayanan"));
            $("#umur_sekarang").html($(this).attr("umurSekarang"));
            $("#tgl_cetak_kartu").html($(this).attr("tglCetakKartu"));
            $("#tgl_tat").html($(this).attr("tglTAT"));
            $("#tgl_tmt").html($(this).attr("tglTMT"));

            $("#hak_kelas").html($(this).attr("hakKelas"));
            $("#provider").html($(this).attr("provUmum"));
            $("#pisa").html($(this).attr("pisa"));
            $("#dinsos").html(($(this).attr("dinsos") !== undefined && $(this).attr("dinsos") !== "") ? $(this).attr("dinsos") : "-");
            $("#no_sktm").html(($(this).attr("noSKTM") !== undefined && $(this).attr("noSKTM") !== "") ? $(this).attr("noSKTM") : "-");
            $("#prolanis_prb").html(($(this).attr("prolanisPRB") !== undefined && $(this).attr("prolanisPRB") !== "") ? $(this).attr("prolanisPRB") : "-");
            $("#esep").html(($(this).attr("eSEP") !== undefined && $(this).attr("eSEP") !== "") ? $(this).attr("eSEP") : "-");
            $("#nm_asuransi_cob").html(($(this).attr("nmAsuransi") !== undefined && $(this).attr("nmAsuransi") !== "") ? $(this).attr("nmAsuransi") : "-");
            $("#no_asuransi_cob").html(($(this).attr("noAsuransi") !== undefined && $(this).attr("noAsuransi") !== "") ? $(this).attr("noAsuransi") : "-");
            $("#tgl_tat_cob").html(($(this).attr("tglTATCOB") !== undefined && $(this).attr("tglTATCOB") !== "") ? $(this).attr("tglTATCOB") : "-");
            $("#tgl_tmt_cob").html(($(this).attr("tglTMTCOB") !== undefined && $(this).attr("tglTMTCOB") !== "") ? $(this).attr("tglTMTCOB") : "-");

            $("#detail_no_rujukan").html($(this).attr("noKunjungan"));
            $("#detail_tgl_rujukan").html($(this).attr("tglKunjungan"));
            $("#detail_jenis_pelayanan").html($(this).attr("pelayanan"));
            $("#detail_diagnosa").html($(this).attr("diagnosa"));
            $("#detail_keluhan").html($(this).attr("keluhan"));
            $("#detail_poli_rujukan").html($(this).attr("poliRujukan"));
            $("#detail_ppk_perujuk").html($(this).attr("provPerujuk"));

            $("#modal-detail-peserta").modal("show");
            DETAILButton.html("<i class=\"fa fa-search\"></i> Detail").removeClass("btn-warning").addClass("btn-info");
        });

        $("body").on("click", ".bpjs_edit_rujukan", function() {
            var no_rujukan = $(this).attr("id");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/keluarrujukanbynokartu?norujuk=" + no_rujukan,
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

                    $("#txt_bpjs_edit_no_rujukan").val(data.noRujukan);
                    $("#txt_bpjs_edit_no_sep").val(data.noSep);
                    $("#txt_tglsep_rujukan_edit").val(data.tglSep);
                    $("#txt_nama_rujukan_edit").val(data.nama);
                    $("#txt_nokartu_rujukan_edit").val(data.noKartu);
                    $("#txt_tgllahir_rujukan_edit").val(data.tglLahir);

                    $("#txt_bpjs_edit_tgl_rujukan").val(data.tglRujukan);
                    $("#txt_bpjs_edit_tgl_rencana_kunjungan").val(data.tglRencanaKunjungan);

                    $("#txt_bpjs_edit_jenis_tujuan_rujukan").select2();

                    $("#txt_bpjs_edit_tujuan_rujukan").append("<option title=\"" + data.namaPpkDirujuk + "\" value=\"" + data.ppkDirujuk + "\">" + data.ppkDirujuk + " - " + data.namaPpkDirujuk + "</option>");
                    $("#txt_bpjs_edit_tujuan_rujukan").select2("data", {
                        id: data.ppkDirujuk,
                        text: data.namaPpkDirujuk
                    });
                    $("#txt_bpjs_edit_tujuan_rujukan").trigger("change");

                    $("#txt_bpjs_edit_jenis_layanan option[value=\"" + data.JnsPelayanan + "\"]").prop("selected", true);
                    $("#txt_bpjs_edit_jenis_layanan").trigger("change");

                    $("#txt_bpjs_edit_catatan").val(data.catatan);

                    $("#txt_bpjs_edit_diagnosa").append("<option title=\"" + data.diagRujukan + "\" value=\"" + data.diagRujukan + "\">" + data.diagRujukan + " - " + data.namaDiagRujukan + "</option>");
                    $("#txt_bpjs_edit_diagnosa").select2("data", {
                        id: data.diagRujukan,
                        text: data.namaDiagRujukan
                    });
                    $("#txt_bpjs_edit_diagnosa").trigger("change");

                    $("#txt_bpjs_edit_tipe_rujukan option[value=\"" + data.tipeRujukan + "\"]").prop("selected", true);
                    $("#txt_bpjs_edit_tipe_rujukan").trigger("change");


                    $("#txt_bpjs_edit_tujuan_poli").append("<option title=\"" + data.poliRujukan + "\" value=\"" + data.poliRujukan + "\">" + data.poliRujukan + " - " + data.namaPoliRujukan + "</option>");
                    $("#txt_bpjs_edit_tujuan_poli").select2("data", {
                        id: data.poliRujukan,
                        text: data.namaPoliRujukan
                    });
                    $("#txt_bpjs_edit_tujuan_poli").trigger("change");

                    $("#modal-rujuk-bpjs-edit").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        /////////EDIT ZONE/////////

        $("#txt_bpjs_edit_tgl_rujukan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_edit_tgl_rencana_kunjungan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_edit_tujuan_rujukan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
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
                        jns: $("#txt_bpjs_edit_jenis_tujuan_rujukan option:selected").val(),
                        kode: term.term
                    };
                },
                cache: true,
                processResults: function(response) {
                    var data = response.response;
                    console.log(data);
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
        }).addClass("form-control").on("select2:select", function(e) {

        });

        $("#txt_bpjs_edit_tujuan_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
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
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_edit_tujuan_poli").trigger("change.select2");
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.kode + "-" + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_edit_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
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
                processResults: function(response) {
                    console.log(response);
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_edit_diagnosa").trigger("change.select2");
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

        $("#txt_bpjs_edit_jenis_layanan").select2();
        $("#txt_bpjs_edit_tipe_rujukan").select2();
        $("#txt_bpjs_edit_tipe_rujukan").change(function() {
            if (parseInt($("#txt_bpjs_edit_tipe_rujukan option:selected").val()) != 2) {
                $(".poli_edit_container").fadeIn();
            } else {
                $(".poli_edit_container").fadeOut();
            }
        });

        /////////END EDIT ZONE/////////


        /////////ADD ZONE/////////

        $("#txt_bpjs_tgl_rujukan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_tgl_rencana_kunjungan").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());


        $("#txt_bpjs_no_sep").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
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
                processResults: function(response) {
                    var data = [response.response];
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: data[0].noSep,
                                id: data[0].noSep,
                                tglSep: data[0].tglSep,
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
            $("#txt_tglsep_rujukan_new").val(data.tglSep);
            $("#txt_nama_rujukan_new").val(data.nama);
            $("#txt_nokartu_rujukan_new").val(data.noKartu);
            $("#txt_tgllahir_rujukan_new").val(data.tglLahir);
        });

        $("#txt_bpjs_tujuan_rujukan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#col_tujuan_rujukan_new"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getfaskes`,
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
                        jns: $("#txt_bpjs_jenis_tujuan_rujukan option:selected").val(),
                        kode: term.term
                    };
                },
                cache: true,
                processResults: function(response) {
                    var data = response.response;
                    console.log(data);
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.kode + "-" + item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {

        });

        $("#txt_bpjs_tujuan_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $("#col_poli_rujukan_new"),
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
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_tujuan_poli").trigger("change.select2");
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.kode + "-" + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#col_diagnosa_rujukan_new"),
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
                processResults: function(response) {
                    console.log(response);
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_diagnosa").trigger("change.select2");
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

        $("#txt_bpjs_jenis_layanan").select2();
        $("#txt_bpjs_jenis_tujuan_rujukan").select2();
        $("#txt_bpjs_tipe_rujukan").select2();
        $("#txt_bpjs_tipe_rujukan").change(function() {
            if (parseInt($("#txt_bpjs_tipe_rujukan option:selected").val()) != 2) {
                $(".poli_container").fadeIn();
            } else {
                $(".poli_container").fadeOut();
            }
        });

        /////////END ADD ZONE/////////

        /////////ADD RUJUKAN KHUSUS ZONE/////////
        $("body").on("click", "#btnTambahRujukanKhusus", function() {
            $("#modal-rujukkan-khusus-bpjs").modal("show");
            MODE = "ADD";
        });

        $("#txt_bpjs_rujuk_khusus_no_rujukan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.Rujukan tidak ditemukan";
                }
            },
            dropdownParent: $("#col-norujukan-khusus"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rujukan/sync.sh/keluarrujukanbynokartu?`,
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
                        norujuk: term.term
                    };
                },
                processResults: function(response) {
                    var data = [];
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
                    } else {
                        data = [response.response];
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: data[0].noRujukan + " - " + data[0].nama,
                                    id: data[0].noRujukan
                                }
                            })
                        };
                    }
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            //
        });

        $("body").on("click", "#btnCoba", function() {
            var diagnosa_list = [];
            $("#list-diagnosa tbody tr").each(function() {
                var kode = $(this).find("td:eq(0)").text();
                var pri_sek = $(this).find("td:eq(2)").text();
                diagnosa_list.push({
                    "kode": pri_sek + ";" + kode,
                });
            });
            console.log(diagnosa_list);
        });


        $("#txt_bpjs_rujuk_khusus_procedure").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Procedure tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujukkan-khusus-bpjs"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getprocedure`,
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
                processResults: function(response) {
                    console.log(response);
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_rujuk_khusus_procedure").trigger("change.select2");
                    } else {
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
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_rujuk_khusus_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujukkan-khusus-bpjs"),
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
                processResults: function(response) {
                    console.log(response);
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_rujuk_khusus_diagnosa").trigger("change.select2");
                    } else {
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
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $(function() {
            var no_urut = 1;
            $("#btnSimpanDiagnosa").click(function() {
                let kode = $("#txt_bpjs_rujuk_khusus_diagnosa  option:selected").val();
                let diagnosa = $("#txt_bpjs_rujuk_khusus_diagnosa  option:selected").text();
                let p_s = $("#txt_bpjs_rujuk_khusus_ps option:selected").val();

                html = "<tr>" +
                    "<td>" + kode + "</td>" +
                    "<td>" + diagnosa + "</td>" +
                    "<td>" + p_s + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-diagnosa'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-diagnosa tbody").append(html);

                return false;
            });

            $("#list-diagnosa tbody").on('click', '.btn-delete-diagnosa', function() {
                $(this).parent().parent().remove();
            });

            $("#btnSimpanProcedure").click(function() {
                let kode = $("#txt_bpjs_rujuk_khusus_procedure option:selected").val();
                let procedure = $("#txt_bpjs_rujuk_khusus_procedure option:selected").text();

                html = "<tr>" +
                    "<td>" + kode + "</td>" +
                    "<td>" + procedure + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-procedure'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-procedure tbody").append(html);

                return false;
            });

            $("#list-procedure tbody").on('click', '.btn-delete-procedure', function() {
                $(this).parent().parent().remove();
            });
        });

        /////////END ADD RUJUKAN KHUSUS ZONE/////////


        $("#btnTambahRujukan").click(function() {
            $("#modal-rujuk-bpjs").modal("show");
            MODE = "ADD";
        });

        $("body").on("click", "#btnProsesRujuk", function() {
            Swal.fire({
                title: "Proses Rujuk BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/insertrujukan",
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
                                "t_rujukan": {
                                    "noSep": $("#txt_bpjs_no_sep option:selected").val(),
                                    "tglRujukan": $("#txt_bpjs_tgl_rujukan").val(),
                                    "tglRencanaKunjungan": $("#txt_bpjs_tgl_rencana_kunjungan").val(),
                                    "ppkDirujuk": $("#txt_bpjs_tujuan_rujukan option:selected").val(),
                                    "jnsPelayanan": $("#txt_bpjs_jenis_layanan option:selected").val(),
                                    "catatan": $("#txt_bpjs_catatan").val(),
                                    "diagRujukan": $("#txt_bpjs_diagnosa option:selected").val(),
                                    "tipeRujukan": $("#txt_bpjs_tipe_rujukan option:selected").val(),
                                    "poliRujukan": $("#txt_bpjs_tujuan_poli option:selected").val(),
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metaData.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Rujukan Berhasil',
                                    'success'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                    $("#modal-rujuk-bpjs").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metaData.message,
                                    'error'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                RujukanList.ajax.reload();
                            });
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });


        $("body").on("click", "#btnEditRujuk", function() {
            Swal.fire({
                title: "Update Rujukan BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/updaterujukan",
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
                                "t_rujukan": {
                                    "noRujukan": $("#txt_bpjs_edit_no_rujukan").val(),
                                    "tglRujukan": $("#txt_bpjs_edit_tgl_rujukan").val(),
                                    "tglRencanaKunjungan": $("#txt_bpjs_edit_tgl_rencana_kunjungan").val(),
                                    "ppkDirujuk": $("#txt_bpjs_edit_tujuan_rujukan option:selected").val(),
                                    "jnsPelayanan": $("#txt_bpjs_edit_jenis_layanan option:selected").val(),
                                    "catatan": $("#txt_bpjs_edit_catatan").val(),
                                    "diagRujukan": $("#txt_bpjs_edit_diagnosa option:selected").val(),
                                    "tipeRujukan": $("#txt_bpjs_edit_tipe_rujukan option:selected").val(),
                                    "poliRujukan": $("#txt_bpjs_edit_tujuan_poli option:selected").val(),
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
                                    'Rujukan Berhasil Diubah',
                                    'success'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                    $("#modal-rujuk-bpjs-edit").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                RujukanList.ajax.reload();
                            });
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".bpjs_hapus_rujukan", function() {
            var no_rujukan = $(this).attr("noRujukan");

            Swal.fire({
                title: "Rujukan Keluar RS",
                text: "Hapus Rujukan, No. Rujukan " + no_rujukan + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/deleterujukan",
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
                                "t_rujukan": {
                                    "noRujukan": no_rujukan,
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS Rujukan Keluar RS',
                                    'Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS Rujukan Keluar RS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
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


        $("body").on("click", "#btnProsesRujukKhusus", function() {
            Swal.fire({
                title: "Proses Rujuk Khusus BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    var diagnosa_list = [];
                    $("#list-diagnosa tbody tr").each(function() {
                        var kode = $(this).find("td:eq(0)").text();
                        var pri_sek = $(this).find("td:eq(2)").text();
                        diagnosa_list.push({
                            "kode": pri_sek + ";" + kode,
                        });
                    });

                    var procedure_list = [];
                    $("#list-procedure tbody tr").each(function() {
                        var kode = $(this).find("td:eq(0)").text();
                        procedure_list.push({
                            "kode": kode
                        });
                    });

                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/insertrujukankhusus",
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
                            "noRujukan": $("#txt_bpjs_rujuk_khusus_no_rujukan").val(),
                            "diagnosa": [diagnosa_list],
                            "procedure": [procedure_list],
                            "user": __MY_NAME__
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'Rujukan Berhasil',
                                    'success'
                                ).then((result) => {
                                    RujukanKhususList.ajax.reload();
                                    $("#modal-rujukkan-khusus-bpjs").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    RujukanKhususList.ajax.reload();
                                });
                            }
                        },
                        error: function(error) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                RujukanKhususList.ajax.reload();
                            });
                            console.clear();
                            console.log(error);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".bpjs_hapus_rujukan_khusus", function() {
            var no_rujukan = $(this).attr("noRujukan");
            var id_rujukan = $(this).attr("id");

            Swal.fire({
                title: "Rujukan Khusus",
                text: "Hapus Rujukan Khusus, No. Rujukan " + no_rujukan + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/deleterujukankhusus",
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
                                "t_rujukan": {
                                    "idRujukan": id_rujukan,
                                    "noRujukan": no_rujukan,
                                    "user": __MY_NAME__
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS Rujukan Khusus',
                                    'Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    RujukanKhususList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS Rujukan Khusus',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    RujukanKhususList.ajax.reload();
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

        $("body").on("click", ".bpjs_print_rujukan", function() {
            var no_rujukan = $(this).attr("id");
            // $("#modal-cetak-rujukan").modal("show");

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rujukan/sync.sh/keluarrujukanbynokartu?norujuk=" + no_rujukan,
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
                    $("#jenis-surat").text(data.namaTipeRujukan);

                    $("#cetak_ppkdirujuk").html(data.namaPpkDirujuk + "<br>" + data.namaPoliRujukan);
                    $("#cetak_rujukan_nama_pasien").html(data.nama);
                    $("#cetak_rujukan_nomor_kartu").html(data.noKartu);
                    $("#cetak_rujukan_diagnosa").html(data.namaDiagRujukan);
                    $("#cetak_rujukan_keterangan").html(data.catatan);

                    var dateNow = new Date();
                    var tgl_cetak = str_pad(2, dateNow.getDate()) + "/" + str_pad(2, dateNow.getMonth() + 1) + "/" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
                    $("#tgl_cetak").html("Tgl. Cetak " + tgl_cetak);

                    $("#cetak_rujukan_berlaku_sampai").html("*) Rujukan Berlaku Sampai Dengan " + data.tglRencanaKunjungan);
                    $("#cetak_rujukan_tgl_rencana_kunjung").html("**) Tanggal Rencana Berkunjung " + data.tglRencanaKunjungan);

                    $("#cetak_rujukan_nomor_rujukan").html(data.noRujukan);
                    $("#cetak_rujukan_asal_rs").html('RSUD PETALA BUMI');

                    $("#cetak_rujukan_kelamin").html((data.kelamin === "L") ? "Laki-laki" : "Perempuan");
                    $("#cetak_rujukan_jenis_pelayanan").html((data.jnsPelayanan === "2") ? "Rawat Jalan" : "Rawat Inap");

                    var tgl_ttd = str_pad(2, dateNow.getDate()) + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + dateNow.getFullYear();
                    $("#tgl_ttd").html("Riau, " + tgl_ttd);

                    $("#modal-cetak-rujukan").modal("show");

                },
                error: function(response) {
                    //
                }
            });
        });

        $("body").on("click", "#btnCetakRujukan", function() {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/bpjs_rujukan.php",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    jenis_surat: $("#jenis-surat").html(),
                    html_data_kiri: $("#data_cetak_rujukan_cetak_kiri").html(),
                    html_data_kanan: $("#data_cetak_rujukan_cetak_kanan").html(),
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

    });
</script>


<div id="modal-rujuk-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rujuk Baru
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Peserta</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-3 form-group">
                                <label for="">No. SEP</label>
                                <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_no_sep"></select>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. SEP</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tglsep_rujukan_new" readonly>
                            </div>
                            <div class="col-3 form-group">
                                <label for="">Nama</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_rujukan_new" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Nomor Kartu</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nokartu_rujukan_new" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. Lahir</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_rujukan_new" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Rujukan</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_rujukan">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Rencana Rujukan</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_rencana_kunjungan">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group" id="col_tujuan_rujukan_new">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_rujukan"></select>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 mb-9 form-group" id="col_tipe_rujukan_new">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-9 form-group poli_container" id="col_poli_rujukan_new">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_poli"></select>
                                </div>
                                <div class="col-12 mb-9 form-group" id="col_diagnosa_rujukan_new">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_diagnosa"></select>
                                </div>
                                <div class="col-12 mb-9 form-group">
                                    <label for="">Catatan</label>
                                    <textarea class="form-control" style="min-height: 200px;" id="txt_bpjs_catatan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesRujuk">
                    <i class="fa fa-plus"></i> Tambah Rujukan Baru
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-rujuk-bpjs-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rujuk Edit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Peserta</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-3 form-group">
                                <label for="">No. SEP</label>
                                <input data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_no_sep" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. SEP</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tglsep_rujukan_edit" readonly>
                            </div>
                            <div class="col-3 form-group">
                                <label for="">Nama</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_rujukan_edit" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Nomor Kartu</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nokartu_rujukan_edit" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. Lahir</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_rujukan_edit" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">No. Rujukan</label>
                                    <input type="text" data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_no_rujukan" readonly>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Rujukan</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_tgl_rujukan">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Rencana Rujukan</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_tgl_rencana_kunjungan">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_edit_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_edit_jenis_tujuan_rujukan">
                                        <option value=""></option>
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_rujukan"></select></select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-9 form-group poli_edit_container">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_poli"></select>
                                </div>
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_diagnosa"></select>
                                </div>
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Catatan</label>
                                    <textarea class="form-control" style="min-height: 200px;" id="txt_bpjs_edit_catatan"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnEditRujuk">
                    <i class="fa fa-plus"></i> Edit Rujukan
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-rujukkan-khusus-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rujuk Khusus Baru
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group" id="col-norujukan-khusus">
                                    <label for="">No. Rujukan</label>
                                    <!-- <input type="text" data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_no_rujukan"> -->
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_no_rujukan"></select>
                                </div>
                                <div class="col-12 form-group">
                                    <div class="row">
                                        <div class="col-10 form-group" id="group_procedure">
                                            <label for="">Procedure</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_procedure"></select>
                                            <!-- <select data-width="100%" class="form-control" id="txt_bpjs_lpk_procedure"></select> -->
                                        </div>
                                        <div class="col-2 form-group d-flex align-items-center mt-4">
                                            <button id="btnSimpanProcedure" type="button" class="btn btn-sm btn-primary">Tambah Procedure</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group">
                                    <table class="table table-bordered table-striped" id="list-procedure">
                                        <thead>
                                            <th width="5%">Kode</th>
                                            <th width="50%">Procedure</th>
                                            <th width="5%">Aksi</th>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 form-group">
                                    <div class="row">
                                        <div class="col-7 form-group">
                                            <label for="">Diagnosa</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_diagnosa"></select>
                                        </div>
                                        <div class="col-3 form-group">
                                            <label for="">Primer/Sekunder</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_ps">
                                                <option value="P">Primer</option>
                                                <option value="S">Sekunder</option>
                                            </select>
                                        </div>
                                        <div class="col-2 form-group d-flex align-items-center mt-2">
                                            <button id="btnSimpanDiagnosa" type="button" class="btn btn-sm btn-primary">Tambah Diagnosa</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 form-group">
                                    <table class="table table-bordered table-striped" id="list-diagnosa">
                                        <thead>
                                            <th width="5%">Kode</th>
                                            <th width="50%">Diagnosa</th>
                                            <th width="50%">Primer/Sekunder</th>
                                            <th width="5%">Aksi</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesRujukKhusus">
                    <i class="fa fa-plus"></i> Tambah Rujukan Khusus
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-cetak-rujukan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row col-lg-8 offset-sm-1">
                    <div class="col-md-5">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" />
                    </div>
                    <div id="title-surat">
                        <h5 class="modal-title" style="margin-bottom: 10px;">
                            SURAT RUJUKAN RUMAH SAKIT
                        </h5>
                        <center>
                            <span style="font-size: 12pt;" id="jenis-surat">Rujukan</span>
                        </center>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6 offset-sm-1" id="data_cetak_rujukan_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 100px;">Kepada Yth.</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_ppkdirujuk"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Mohon pemeriksaan dan penanganan lebih lanjut penderita :</td>
                            </tr>
                            <tr>
                                <td>Nama Pasien</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_nama_pasien"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu BPJS</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_nomor_kartu"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_diagnosa"></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_keterangan"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Demikian atas bantuannya diucapkan banyak terima kasih.</td>
                            </tr>
                            <tr>
                                <td colspan="3" id="cetak_rujukan_berlaku_sampai" style="padding-top: 50px;">*) Rujukan Berlaku Sampai Dengan 20 Desember 2023</td>
                            </tr>
                            <tr>
                                <td colspan="3" id="cetak_rujukan_tgl_rencana_kunjung">**) Tanggal Rencana Berkunjung 20 Desember 2023</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_cetak_rujukan_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 100px;">Nomor Rujukan</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_nomor_rujukan"></td>
                            </tr>
                            <tr>
                                <td>Asal Rumah Sakit</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_asal_rs"></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 50px;">Kelamin</td>
                                <td class="wrap_content" style="padding-top: 50px;">:</td>
                                <td id="cetak_rujukan_kelamin" style="padding-top: 50px;"></td>
                            </tr>
                            <tr>
                                <td>Rawat</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_jenis_pelayanan"></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 80px;" id="tgl_ttd">Riau, 13 Desember 2023</td>
                            </tr>
                            <tr>
                                <td style="padding-top: 50px;">_______________</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnCetakRujukan">
                    <i class="fa fa-print"></i> Cetak
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-detail-peserta" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-lg-8 offset-sm-3">
                    <h5 class="modal-title" id="modal-large-title">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> <span>Detail Rujukan Peserta</span>
                    </h5>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-3 offset-sm-2">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 150px;">Nama Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="nama_peserta"></td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td class="wrap_content">:</td>
                                <td id="nik"></td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="no_kartu"></td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td class="wrap_content">:</td>
                                <td id="jenis_kelamin"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_lahir"></td>
                            </tr>
                            <tr>
                                <td>No. Telp</td>
                                <td class="wrap_content">:</td>
                                <td id="nomor_telepon"></td>
                            </tr>
                            <tr>
                                <td>No. Mr</td>
                                <td class="wrap_content">:</td>
                                <td id="no_mr"></td>
                            </tr>
                            <tr>
                                <td>Status Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="status_peserta"></td>
                            </tr>
                            <tr>
                                <td>Jenis Peserta</td>
                                <td class="wrap_content">:</td>
                                <td id="jenis_peserta"></td>
                            </tr>
                            <tr>
                                <td>Umur Saat Pelayanan</td>
                                <td class="wrap_content">:</td>
                                <td id="umur_saat_pelayanan"></td>
                            </tr>
                            <tr>
                                <td>Umur Sekarang</td>
                                <td class="wrap_content">:</td>
                                <td id="umur_sekarang"></td>
                            </tr>
                            <tr>
                                <td>Tanggal Cetak Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_cetak_kartu"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TAT</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tat"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TMT</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tmt"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-3">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">Hak Kelas</td>
                                <td class="wrap_content">:</td>
                                <td id="hak_kelas"></td>
                            </tr>
                            <tr>
                                <td>Provider</td>
                                <td class="wrap_content">:</td>
                                <td id="provider"></td>
                            </tr>
                            <tr>
                                <td>Pisa</td>
                                <td class="wrap_content">:</td>
                                <td id="pisa"></td>
                            </tr>
                            <tr>
                                <td>Dinsos</td>
                                <td class="wrap_content">:</td>
                                <td id="dinsos"></td>
                            </tr>
                            <tr>
                                <td>No. SKTM</td>
                                <td class="wrap_content">:</td>
                                <td id="no_sktm"></td>
                            </tr>
                            <tr>
                                <td>Prolanis PRB</td>
                                <td class="wrap_content">:</td>
                                <td id="prolanis_prb"></td>
                            </tr>
                            <tr>
                                <td>eSEP</td>
                                <td class="wrap_content">:</td>
                                <td id="esep"></td>
                            </tr>
                            <tr>
                                <td>Informasi COB</td>
                            </tr>
                            <tr>
                                <td>Nama Asuransi</td>
                                <td class="wrap_content">:</td>
                                <td id="nm_asuransi_cob"></td>
                            </tr>
                            <tr>
                                <td>No. Asuransi</td>
                                <td class="wrap_content">:</td>
                                <td id="no_asuransi_cob"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TAT COB</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tat_cob"></td>
                            </tr>
                            <tr>
                                <td>Tanggal TMT COB</td>
                                <td class="wrap_content">:</td>
                                <td id="tgl_tmt_cob"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-3">
                        <table class="table form-mode">
                            <tr>
                                <td style="width: 120px;">No.Rujukan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_no_rujukan"></td>
                            </tr>
                            <tr>
                                <td>Tgl. Rujukan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_tgl_rujukan"></td>
                            </tr>
                            <tr>
                                <td>Jenis Pelayanan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_jenis_pelayanan"></td>
                            </tr>
                            <tr>
                                <td>Diagnosa</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_diagnosa"></td>
                            </tr>
                            <tr>
                                <td>Keluhan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_keluhan"></td>
                            </tr>
                            <tr>
                                <td>Poli Rujukan</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_poli_rujukan"></td>
                            </tr>
                            <tr>
                                <td>PPK Perujuk</td>
                                <td class="wrap_content">:</td>
                                <td id="detail_ppk_perujuk"></td>
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