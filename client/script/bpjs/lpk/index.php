<script type="text/javascript">
    $(function() {

        var MODE = "ADD";
        var getUrl = __BPJS_SERVICE_URL__ + "lpk/sync.sh/klaimlpk?tglmasuk=2023-08-19&jnspelayanan=2";
        $("#text_search_lpk_tglmasuk").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#btn_search_lpk").click(function() {
            $('#alert-lpk-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "lpk/sync.sh/klaimlpk?tglmasuk=" + $("#text_search_lpk_tglmasuk").val() + "&jnspelayanan=" + $("#text_search_lpk_jnslayanan option:selected").val();
            MODE = "SEARCH";
            LpkList.ajax.url(getUrl).load();
        });

        $('#alert-lpk-container').hide();

        var LpkList = $("#table-lpk").DataTable({
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
                            $('#alert-lpk').text(response.metadata.message);
                            $('#alert-lpk-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-lpk-container').fadeOut();
                        // var data = response.response.lpk.list;
                        return response.response.lpk.list;
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
                        return row.peserta.noKartu;
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
                        return row.peserta.noMR;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglMasuk;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglKeluar;
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
                        return row.poli.poli.kode;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.DPJP.dokter.kode + " - " + row.DPJP.dokter.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-warning btn-sm bpjs_print_lpk\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button class=\"btn btn-info btn-sm bpjs_edit_lpk\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger bpjs_hapus_lpk\" id=\"" + row.noSep + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });


        $("body").on("click", ".bpjs_hapus_lpk", function() {
            var no_sep = $(this).attr("id");

            Swal.fire({
                title: "Hapus LPK?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __BPJS_SERVICE_URL__ + "lpk/sync.sh/deletelpk",
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
                            "t_lpk": {
                                "noSep": no_sep
                            }
                        },
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    "BPJS Rujukan",
                                    "Berhasil dihapus",
                                    "success"
                                ).then((result) => {
                                    LpkList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "BPJS Rujukan",
                                    response.metadata.message,
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

        $("body").on("click", ".bpjs_edit_lpk", function() {
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

                    $("#modal-lpk-bpjs-edit").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });

        });


        /////////ADD ZONE/////////

        $("#txt_bpjs_lpk_tgl_masuk").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_lpk_tgl_keluar").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());


        $("#txt_bpjs_lpk_no_sep").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-lpk-bpjs"),
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
                                nokartu: data.peserta.nokartu,
                                jnsPelayanan: data.jnsPelayanan
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_bpjs_lpk_tgl_masuk").val((data.tglSep !== undefined && data.tglSep !== null) ? data.tglSep : "");
            $("#txt_bpjs_lpk_tgl_keluar").val((data.tglSep !== undefined && data.tglSep !== null) ? data.tglSep : "");
            if (data.jnsPelayanan === "Rawat Jalan") {
                var jns_layan = "2";
            } else {
                var jns_layan = "1";
            }
            $("#txt_bpjs_lpk_jenis_layan option[value=\"" + jns_layan + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_jenis_layan").trigger("change");
        });

        $("#txt_bpjs_lpk_jenis_layan").select2();
        $("#txt_bpjs_lpk_jenis_layan").change(function() {
            if (parseInt($("#txt_bpjs_lpk_jenis_layan option:selected").val()) != 2) {
                $(".perawatan").fadeIn();
            } else {
                $(".perawatan").fadeOut();
            }
        });

        $("#txt_bpjs_lpk_tgl_masuk").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_lpk_tgl_keluar").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_lpk_ruang_rawat").select2({
            // minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Ruang rawat tidak ditemukan";
                }
            },
            dropdownParent: $("#group_ruang_rawat"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getruangrawat`,
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
                // data: function(term) {
                //     return {
                //         search: term.term
                //     };
                // },
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_lpk_ruang_rawat").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_lpk_ruang_rawat").select2({
                            data: parsedData
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_lpk_kelas_rawat").select2({
            dropdownParent: $("#group_kelas_rawat"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkelasrawat`,
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
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code === null) {
                        $("#txt_bpjs_lpk_kelas_rawat").trigger("change.select2");
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

        $("#txt_bpjs_lpk_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $("#group_poli"),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getpoli`,
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
                        $("#txt_bpjs_lpk_poli").trigger("change.select2");
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


        $("#txt_bpjs_lpk_spesialistik").select2({
            dropdownParent: $("#group_spesialistik"),
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
                        $("#txt_bpjs_lpk_spesialistik").trigger("change.select2");
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

        var dateNow = new Date();
        var tgl_pelayanan = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

        $("#txt_bpjs_lpk_dpjp").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "DPJP tidak ditemukan";
                }
            },
            dropdownParent: $("#group_dpjp"),
            ajax: {
                // url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokter?jnspelayanan=" + $('#txt_bpjs_lpk_jenis_layan option:selected').val() + "&tglpelayanan=" + tgl_pelayanan + "&kode=" + $("#txt_bpjs_lpk_spesialistik option:selected").val(),
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
                data: function(term) {
                    return {
                        namadpjp: term.term
                    };
                },
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code === null) {
                        $("#txt_bpjs_lpk_dpjp").trigger("change.select2");
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

        $("#txt_bpjs_lpk_cara_keluar").select2({
            dropdownParent: $("#group_cara_keluar"),
            ajax: {
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
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_lpk_cara_keluar").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_lpk_cara_keluar").select2({
                            data: parsedData
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_lpk_kondisi_pulang").select2({
            dropdownParent: $("#group_kondisi_pulang"),
            ajax: {
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
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_lpk_kondisi_pulang").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kode,
                                text: `${data[a].kode} - ${data[a].nama}`
                            })
                        }
                        $("#txt_bpjs_lpk_kondisi_pulang").select2({
                            data: parsedData
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("#txt_bpjs_lpk_procedure").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Procedure tidak ditemukan";
                }
            },
            dropdownParent: $("#group_procedure"),
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
                        $("#txt_bpjs_lpk_procedure").trigger("change.select2");
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

        $("#txt_bpjs_lpk_diagnosa").select2({
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
                processResults: function(response) {
                    console.log(response);
                    if (response.metadata.code === null) {
                        $("#txt_bpjs_lpk_diagnosa").trigger("change.select2");
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

        $("#txt_bpjs_lpk_dirujukke_faskes").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#group_dirujukke_faskes"),
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
                        jns: $("#txt_bpjs_lpk_dirujukke_jenis_faskes option:selected").val(),
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

        $("#txt_bpjs_lpk_tgl_kontrol_kembali").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_lpk_poli_kontrol_kembali").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $("#group_poli_kontrol_kembali"),
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
                        $("#txt_bpjs_lpk_poli_kontrol_kembali").trigger("change.select2");
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

        $(function() {
            $("#btnSimpanDiagnosa").click(function() {
                let kode = $("#txt_bpjs_lpk_diagnosa  option:selected").val();
                let diagnosa = $("#txt_bpjs_lpk_diagnosa  option:selected").text();
                let p_s = $("#txt_bpjs_lpk_ps option:selected").val();

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
                let kode = $("#txt_bpjs_lpk_procedure option:selected").val();
                let procedure = $("#txt_bpjs_lpk_procedure option:selected").text();

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

        /////////END ADD ZONE/////////


        $("#btnTambahLPK").click(function() {
            $("#modal-lpk-bpjs").modal("show");
            MODE = "ADD";
        });

        $("body").on("click", "#btnProsesLpk", function() {
            Swal.fire({
                title: "Proses LPK BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var diagnosa_list = [];
                    $("#list-diagnosa tbody tr").each(function() {
                        var kode = $(this).find("td:eq(0)").text();
                        var level = $(this).find("td:eq(2)").text();
                        diagnosa_list.push({
                            "kode": kode,
                            "level": level
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
                        url: __BPJS_SERVICE_URL__ + "lpk/sync.sh/insertlpk",
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
                            "t_lpk": {
                                "noSep": $('#txt_bpjs_lpk_no_sep option:selected').val(),
                                "tglMasuk": $('#txt_bpjs_lpk_tgl_masuk').val(),
                                "tglKeluar": $('#txt_bpjs_lpk_tgl_keluar').val(),
                                "jaminan": $('#txt_bpjs_lpk_jaminan option:selected').val(),
                                "poli": {
                                    "poli": $('#txt_bpjs_lpk_poli option:selected').val()
                                },
                                "perawatan": {
                                    "ruangRawat": $('#txt_bpjs_lpk_ruang_rawat option:selected').val(),
                                    "kelasRawat": $('#txt_bpjs_lpk_kelas_rawat option:selected').val(),
                                    "spesialistik": $('#txt_bpjs_lpk_spesialistik option:selected').val(),
                                    "caraKeluar": $('#txt_bpjs_lpk_cara_keluar option:selected').val(),
                                    "kondisiPulang": $('#txt_bpjs_lpk_kondisi_pulang option:selected').val()
                                },
                                "diagnosa": [diagnosa_list],
                                "procedure": [procedure_list],
                                "rencanaTL": {
                                    "tindakLanjut": $('#txt_bpjs_lpk_tindak_lanjut option:selected').val(),
                                    "dirujukKe": {
                                        "kodePPK": $('#txt_bpjs_lpk_dirujukke_faskes option:selected').val()
                                    },
                                    "kontrolKembali": {
                                        "tglKontrol": $('#txt_bpjs_lpk_tgl_kontrol_kembali').val(),
                                        "poli": $('#txt_bpjs_lpk_poli_kontrol_kembali option:selected').val()
                                    }
                                },

                                "DPJP": $('#txt_bpjs_lpk_dpjp option:selected').val(),
                                "user": "1"
                            }
                        },
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS',
                                    'LPK Berhasil',
                                    'success'
                                ).then((result) => {
                                    LpkList.ajax.reload();
                                    $("#modal-lpk-bpjs").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    LpkList.ajax.reload();
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                'LPK',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                LpkList.ajax.reload();
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
                                "user": "1"
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
                                    LpkList.ajax.reload();
                                    $("#modal-lpk-bpjs-edit").modal("hide");
                                });
                            } else {
                                Swal.fire(
                                    'BPJS',
                                    response.response_package.bpjs.content.metaData.message,
                                    'error'
                                ).then((result) => {
                                    LpkList.ajax.reload();
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire(
                                'BPJS',
                                'Aksi Gagal',
                                'error'
                            ).then((result) => {
                                LpkList.ajax.reload();
                            });
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".bpjs_print_lpk", function() {
            var no_rujukan = $(this).attr("id");
            // $("#modal-cetak-lpk").modal("show");

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

                    $("#rk_dokter").html(data.mamaDokter + "<br>" + data.mamaPoliTujuan);
                    $("#rk_nomor_kartu").html(data.sep.noKartu);
                    $("#rk_nama_pasien").html(data.sep.nama + " (" + data.sep.kelamin + ")");
                    $("#rk_tanggal_lahir").html(data.sep.tglLahir);
                    $("#rk_diagnosa_awal").html(data.sep.diagnosa);

                    var dateNow = new Date();
                    var tgl_cetak = str_pad(2, dateNow.getDate()) + "/" + str_pad(2, dateNow.getMonth() + 1) + "/" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
                    $("#tgl_cetak").html("Tgl. Cetak " + tgl_cetak);

                    $("#rk_nomor_surat").html(data.noSuratkontrol);
                    $("#rk_tanggal_terbit").html(data.tglTerbit);

                    $("#modal-cetak-lpk").modal("show");
                },
                error: function(response) {
                    //
                }
            });
        });

        $("body").on("click", "#btnCetakRujukan", function() {
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

<div id="modal-lpk-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> LPK <code>Baru</code>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi LPK</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-3 form-group">
                                <label for="">No. SEP</label>
                                <select data-width="100%" class="form-control" id="txt_bpjs_lpk_no_sep"></select>
                            </div>
                            <div class="col-3 form-group">
                                <label for="">Tanggal Masuk</label>
                                <input type="text" data-width="100%" class="form-control" id="txt_bpjs_lpk_tgl_masuk">
                            </div>
                            <div class="col-3 form-group">
                                <label for="">Tanggal Keluar</label>
                                <input type="text" data-width="100%" class="form-control" id="txt_bpjs_lpk_tgl_keluar">
                            </div>
                            <div class="col-3 form-group mb-5">
                                <label for="">Penjamin</label>
                                <select data-width="100%" class="form-control" id="txt_bpjs_lpk_jaminan">
                                    <option value="1">JKN</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control uppercase" id="txt_bpjs_lpk_jenis_layan">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group perawatan" id="group_ruang_rawat">
                                    <label for="">Ruang Rawat</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_ruang_rawat"></select>
                                </div>
                                <div class="col-12 form-group perawatan" id="group_kelas_rawat">
                                    <label for="">Kelas Rawat</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_kelas_rawat"></select>
                                </div>
                                <div class="col-12 form-group perawatan" id="group_spesialistik">
                                    <label for="">Spesialistik</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_spesialistik"></select>
                                </div>
                                <div class="col-12 form-group perawatan" id="group_cara_keluar">
                                    <label for="">Cara Keluar</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_cara_keluar"></select>
                                </div>
                                <div class="col-12 form-group perawatan" id="group_kondisi_pulang">
                                    <label for="">Kondisi Pulang</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_kondisi_pulang"></select>
                                </div>
                                <div class="col-12 form-group" id="group_poli">
                                    <label for="">Poli</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_poli"></select>
                                </div>
                                <div class="col-12 form-group" id="group_dpjp">
                                    <label for="">DPJP</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_dpjp"></select>
                                </div>
                            </div>

                            <div class="col-6">
                                <!-- <div class="col-2 form-group ">
                                    <button id="btnCoba" type="button" class="btn btn-sm btn-primary">Coba</button>
                                </div> -->
                                <div class="row">
                                    <div class="col-7 form-group" id="group_diagnosa">
                                        <label for="">Diagnosa</label>
                                        <select data-width="100%" class="form-control" id="txt_bpjs_lpk_diagnosa"></select>
                                    </div>
                                    <div class="col-2 form-group">
                                        <label for="">Primer/Sekunder</label>
                                        <select data-width="100%" class="form-control" id="txt_bpjs_lpk_ps">
                                            <option value="1">1 - Primer</option>
                                            <option value="2">2 - Sekunder</option>
                                        </select>
                                    </div>
                                    <div class="col-2 form-group d-flex align-items-center mt-4">
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
                                <div class="row">
                                    <div class="col-9 form-group" id="group_procedure">
                                        <label for="">Procedure</label>
                                        <select data-width="100%" class="form-control" id="txt_bpjs_lpk_procedure"></select>
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
                                <div class="col-12 form-group">
                                    <label for="">Tindak Lanjut</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_tindak_lanjut">
                                        <option value="1">Diperbolehkan Pulang</option>
                                        <option value="2">Pemeriksaan Penunjang</option>
                                        <option value="3">Dirujuk Ke</option>
                                        <option value="4">Kontrol Kembali</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group" id="group_jenis_faskes">
                                    <label for="">Jenis Faskes Dirujuk ke</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_lpk_dirujukke_jenis_faskes">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group" id="group_dirujukke_faskes">
                                    <label for="">Faskes Dirujuk ke</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_dirujukke_faskes"></select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Kontrol Kembali</label>
                                    <input type="text" data-width="100%" class="form-control" id="txt_bpjs_lpk_tgl_kontrol_kembali">
                                </div>
                                <div class="col-12 form-group" id="group_poli_kontrol_kembali">
                                    <label for="">Poli Kontrol Kembali</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_poli_kontrol_kembali"></select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesLpk">
                    <i class="fa fa-plus"></i> Tambah LPK
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-cetak-lpk" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row col-lg-8 offset-sm-2">
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
                    <div class="col-6 offset-sm-2" id="data_cetak_rujukan_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td>Kepada Yth.</td>
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_dokter"></td>
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
                                <td>Nomor Rujukan</td>
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
                                <td class="wrap_content">:</td>
                                <td id="cetak_rujukan_kelamin"></td>
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