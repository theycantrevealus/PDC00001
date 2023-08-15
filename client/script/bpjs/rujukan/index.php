<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/pdfjs/pdf2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var MODE = "ADD";

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
            MODE = "SEARCH";
            RujukanList.ajax.url(getUrl).load();
        });

        var getUrl = __BPJS_SERVICE_URL__ + "rujukan/sync.sh/listkeluarrujukan?tglmulai=2022-02-01&tglakhir=2022-03-01";
        var currentRujukan = "",
            currentRujukanText = "";
        var selectedBPJS = "",
            selectedPasien = "";

        $('#alert-rujukanlist-container').hide();

        var RujukanList = $("#table-rujukan").DataTable({
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
                        return row.nama + " - " + row.noKartu;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noRujukan;
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
                            "<button class=\"btn btn-danger bpjs_hapus_rujukan\" id=\"" + row.noRujukan + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

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
            MODE = "SEARCH";
            RujukanKhususList.ajax.url(getUrl_rujukankhusus).load();
        });
        $('#alert-rujukankhusus-container').hide();

        var RujukanKhususList = $("#table-rujukan-khusus").DataTable({
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
                        if (MODE === "SEARCH") {

                            $('#alert-rujukankhusus-container').fadeIn();
                            $('#alert-rujukankhusus-list').text(response.metadata.message);
                        }
                        return [];
                    } else {
                        $('#alert-rujukankhusus-container').fadeOut();
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
                            "<button class=\"btn btn-danger bpjs_hapus_rujukan\" no-rujukan=\"" + row.norujukan + "\"  id=\"" + row.idrujukan + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".bpjs_hapus_rujukan", function() {
            var no_rujukan = $(this).attr("id");

            Swal.fire({
                title: "Hapus Rujukan?",
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
                        data: {
                            "t_rujukan": {
                                "noRujukan": no_rujukan,
                                "user": __MY_NAME__
                            }
                        },
                        success: function(response) {
                            if (parseInt(response.metaData.code) === 200) {
                                Swal.fire(
                                    "BPJS Rujukan",
                                    "Berhasil dihapus",
                                    "success"
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                    RujukanLain.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "BPJS Rujukan",
                                    response.metaData.message,
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }

                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
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

                    $("#txt_bpjs_edit_tgl_rujukan").val(data.tglRujukan);
                    $("#txt_bpjs_edit_tgl_rencana_kunjungan").val(data.tglRencanaKunjungan);

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
                    if (response.metadata.code !== 200) {
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
                    if (response.metadata.code === null) {
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
                    var data = response.response;
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: data.noSep + "-" + data.peserta.nama,
                                id: data.noSep,
                                tglSep: data.tglSep,
                                nokartu: data.peserta.nokartu
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_tujuan_rujukan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
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
            dropdownParent: $("#modal-rujuk-bpjs"),
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
                    if (response.metadata.code !== 200) {
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
            dropdownParent: $("#modal-rujuk-bpjs"),
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
                    if (response.metadata.code === null) {
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
                    if (response.metadata.code === null) {
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
                    if (response.metadata.code === null) {
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

                    var tgl_rujukan = new Date($("#txt_bpjs_tgl_rujukan").datepicker("getDate"));
                    var parse_tgl_rujukan = tgl_rujukan.getFullYear() + "-" + str_pad(2, tgl_rujukan.getMonth() + 1) + "-" + str_pad(2, tgl_rujukan.getDate());

                    var tgl_rencana_kunjungan = new Date($("#txt_bpjs_tgl_rencana_kunjungan").datepicker("getDate"));
                    var parse_tgl_rencana_kunjungan = tgl_rencana_kunjungan.getFullYear() + "-" + str_pad(2, tgl_rencana_kunjungan.getMonth() + 1) + "-" + str_pad(2, tgl_rencana_kunjungan.getDate());

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
                        data: {
                            "t_rujukan": {
                                "noSep": $("#txt_bpjs_no_sep option:selected").val(),
                                "tglRujukan": parse_tgl_rujukan,
                                "tglRencanaKunjungan": parse_tgl_rencana_kunjungan,
                                "ppkDirujuk": $("#txt_bpjs_tujuan_rujukan option:selected").val(),
                                "jnsPelayanan": $("#txt_bpjs_jenis_layanan option:selected").val(),
                                "catatan": $("#txt_bpjs_catatan").val(),
                                "diagRujukan": $("#txt_bpjs_diagnosa option:selected").val(),
                                "tipeRujukan": $("#txt_bpjs_tipe_rujukan option:selected").val(),
                                "poliRujukan": $("#txt_bpjs_tujuan_poli option:selected").val(),
                                "user": __MY_NAME__
                            }
                        },
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


        $("#btnEditRujuk").click(function() {
            Swal.fire({
                title: "Update Rujukan BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    var tgl_rujukan_edit = new Date($("#txt_bpjs_edit_tgl_rujukan").datepicker("getDate"));
                    var parse_tgl_rujukan_edit = tgl_rujukan_edit.getFullYear() + "-" + str_pad(2, tgl_rujukan_edit.getMonth() + 1) + "-" + str_pad(2, tgl_rujukan.getDate());

                    var tgl_rencana_kunjungan_edit = new Date($("#txt_bpjs_edit_tgl_rencana_kunjungan").datepicker("getDate"));
                    var parse_tgl_rencana_kunjungan_edit = tgl_rencana_kunjungan_edit.getFullYear() + "-" + str_pad(2, tgl_rencana_kunjungan_edit.getMonth() + 1) + "-" + str_pad(2, tgl_rencana_kunjungan_edit.getDate());

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
                        data: {
                            "t_rujukan": {
                                "noRujukan": $("#txt_bpjs_edit_no_rujukan").val(),
                                "tglRujukan": parse_tgl_rujukan_edit,
                                "tglRencanaKunjungan": parse_tgl_rencana_kunjungan_edit,
                                "ppkDirujuk": $("#txt_bpjs_edit_tujuan_rujukan option:selected").val(),
                                "jnsPelayanan": $("#txt_bpjs_edit_jenis_layanan option:selected").val(),
                                "catatan": $("#txt_bpjs_edit_catatan").val(),
                                "diagRujukan": $("#txt_bpjs_edit_diagnosa option:selected").val(),
                                "tipeRujukan": $("#txt_bpjs_edit_tipe_rujukan option:selected").val(),
                                "poliRujukan": $("#txt_bpjs_edit_tujuan_poli option:selected").val(),
                                "user": __MY_NAME__
                            }
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
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
                                    response.response_package.bpjs.content.metaData.message,
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
                        data: {
                            "noRujukan": $("#txt_bpjs_rujuk_khusus_no_rujukan").val(),
                            "diagnosa": [diagnosa_list],
                            "procedure": [procedure_list],
                            "user": __MY_NAME__
                        },
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
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">No. SEP</label>
                                    <select data-width="100%" class="form-control uppercase septxt_bpjs_no_sep" id="txt_bpjs_no_sep"></select>
                                </div>
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
                                    <select class="form-control sep" id="txt_bpjs_jenis_layanan"> <!-- jnsPelayanan -->
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
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_rujukan"></select>
                                    </select> <!-- ppkDirujuk -->
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tipe_rujukan"> <!-- tipeRujukan -->
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-9 form-group poli_container">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_poli"></select> <!-- poliRujukan -->
                                </div>
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_diagnosa"></select> <!-- diagRujukan -->
                                </div>
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
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
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">No. Rujukan</label>
                                    <input type="text" data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_no_rujukan" readonly>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">No. SEP</label>
                                    <input data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_no_sep" readonly>
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
                                    <select class="form-control sep" id="txt_bpjs_edit_jenis_layanan"> <!-- jnsPelayanan -->
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_edit_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_rujukan"></select>
                                    </select> <!-- ppkDirujuk -->
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tipe_rujukan"> <!-- tipeRujukan -->
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-9 form-group poli_edit_container">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_poli"></select> <!-- poliRujukan -->
                                </div>
                                <div class="col-12 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_diagnosa"></select> <!-- diagRujukan -->
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
                                <div class="col-12 form-group">
                                    <label for="">No. Rujukan</label>
                                    <input type="text" data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_no_rujukan">
                                </div>
                                <!-- <div class="col-12 form-group">
                                    <label for="">Procedure</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_rujuk_khusus_procedure"></select>
                                </div> -->
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
                                <!-- <div class="col-2 form-group ">
                                    <button id="btnCoba" type="button" class="btn btn-sm btn-primary">Coba</button>
                                </div> -->
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
                            <span style="font-size: 12pt;" id="jenis-surat">Rujukan Penuh</span>
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