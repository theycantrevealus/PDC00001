<script type="text/javascript">
    $(function() {
        var selectedKartu = "";
        var refreshData = 'N';
        var MODE = "ADD";

        var mode_search = "ADD";
        var search_by = "ADD";

        $("#tglawal_prb").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#tglakhir_prb").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        var getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=" + $("#tglawal_prb").val() + "&tglakhir=" + $("#tglakhir_prb").val();
        // var getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=2023-08-01&tglakhir=2023-08-31";

        $("#btn_search_tgl_prb").click(function() {
            $('#alert-prb-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/caritglprb?tglawal=" + $("#tglawal_prb").val() + "&tglakhir=" + $("#tglakhir_prb").val();
            mode_search = "SEARCH";
            search_by = "TGL_SRB";
            DataPRB.ajax.url(getUrl).load();
        });

        $("#btn_search_no_srb").click(function() {
            $('#alert-prb-container').fadeOut();
            getUrl = __BPJS_SERVICE_URL__ + "prb/sync.sh/cariprb?nosrb=" + $("#text_search_no_srb").val() + "&nosep=" + $("#text_search_no_sep").val();
            mode_search = "SEARCH";
            search_by = "NO_SRB";
            DataPRB.ajax.url(getUrl).load();
        });

        $('#alert-prb-container').hide();

        var DataPRB = $("#table-prb").DataTable({
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
                        if (mode_search === "SEARCH") {
                            $('#alert-prb').text(response.metadata.message);
                            $('#alert-prb-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-prb-container').fadeOut();
                        if (search_by === "TGL_SRB") {
                            return response.response.prb.list;
                        } else if (search_by === "NO_SRB") {
                            return [response.response.prb];
                        }
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
                        return row.noSRB;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSEP;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglSRB;
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
                        return row.programPRB.kode + " - " + row.programPRB.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.DPJP.kode + " - " + row.DPJP.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnEditPRB\" no-sep=\"" + row.noSEP + "\" id=\"" + row.noSRB + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusPRB\" no-sep=\"" + row.noSEP + "\" id=\"" + row.noSRB + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnHapusPRB", function() {
            var no_sep = $(this).attr("no-sep");
            var no_SRB = $(this).attr("id");
            Swal.fire({
                title: "BPJS PRB",
                text: "Hapus PRB " + no_SRB + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${__BPJS_SERVICE_URL__}prb/sync.sh/deleteprb`,
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
                                "t_prb": {
                                    "noSrb": no_SRB,
                                    "noSep": no_sep,
                                    "user": "0069R035"
                                }
                            }
                        }),
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS PRB',
                                    'PRB Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    DataPRB.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS PRB',
                                    response.metadata.message,
                                    'error'
                                ).then((result) => {
                                    // DataPRB.ajax.reload();
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

        $("body").on("click", "#btnTestEdit", function() {
            var no_SRB = $(this).attr("id");
            var no_sep = $(this).attr("no-sep");
            MODE = "EDIT";

            var data = {
                "DPJP": {
                    "kode": "275190",
                    "nama": "Marwoto, dr. Sp.PD"
                },
                "noSEP": "1101R0070118V999996",
                "noSRB": "9419118",
                "obat": {
                    "obat": [{
                            "jmlObat": "5",
                            "kdObat": "00019990017",
                            "nmObat": "Analog Insulin Long Acting inj 100 UI/ml",
                            "signa1": "3",
                            "signa2": "1"
                        },
                        {
                            "jmlObat": "10",
                            "kdObat": "00078990062",
                            "nmObat": "Human Insulin Long Acting penfill 3 ml",
                            "signa1": "3",
                            "signa2": "1"
                        }
                    ]
                },
                "peserta": {
                    "alamat": "Jl. Merdekah",
                    "asalFaskes": {
                        "kode": "016999901",
                        "nama": "Klinik KALI ADEM"
                    },
                    "email": "emailkudisadap@gmail.com",
                    "kelamin": "P",
                    "nama": "SITI RONALDO",
                    "noKartu": "0054679979951",
                    "noTelepon": "089101999101",
                    "tglLahir": "1949-09-06"
                },
                "programPRB": {
                    "kode": "01",
                    "nama": "Diabetes Mellitus"
                },
                "keterangan": "Kecapekan Kerja",
                "saran": "Pasien Harus Cuti, Kebanyakan Kerja",
                "tglSRB": "2018-01-08"
            };

            $('#col_nosrb').fadeIn();

            $("#txt_bpjs_prb_sep").append("<option value=\"" + data.noSEP + "\">" + data.noSEP + "</option>");
            $("#txt_bpjs_prb_sep").select2("data", {
                id: data.noSEP,
                text: data.noSEP
            });
            $("#txt_bpjs_prb_sep").prop("disabled", true);
            $("#txt_bpjs_prb_sep").trigger("change");

            $("#txt_bpjs_prb_nosrb").val(data.noSRB);
            $("#txt_bpjs_prb_nama").val(data.peserta.nama);
            $("#txt_bpjs_prb_nokartu").val(data.peserta.noKartu);
            $("#txt_bpjs_prb_tgllahir").val(data.peserta.tglLahir);

            $("#switch_nmtgl").text('Tgl. SRB');
            $("#txt_bpjs_prb_tgl_sep").val(data.tglSRB);
            $("#txt_bpjs_prb_email").val(data.peserta.email);
            $("#txt_bpjs_prb_alamat").val(data.peserta.alamat);

            loadProgramPRB("#txt_bpjs_prb_program", data.programPRB.kode);
            $("#txt_bpjs_prb_program").prop("disabled", true);
            $("#txt_bpjs_prb_program").trigger("change");

            $("#txt_bpjs_prb_dpjp").append("<option value='" + data.DPJP.kode + "'>" + data.DPJP.nama + "</option>");
            $("#txt_bpjs_prb_dpjp").select2("data", {
                id: data.DPJP.kode,
                text: data.DPJP.nama
            });
            $("#txt_bpjs_prb_dpjp option[value=\"" + data.DPJP.kode + "\"]").prop("selected", true);
            $("#txt_bpjs_prb_dpjp").trigger("change");

            $("#txt_bpjs_prb_keterangan").val(data.keterangan);
            $("#txt_bpjs_prb_saran").val(data.saran);

            $("#list-obat tbody tr").remove();
            data.obat.obat.forEach((item) => {
                html = "<tr id='row_" + item.kdObat + "'>" +
                    "<td><div id='col_resep_obat_" + item.kdObat + "'><select id='resep_obat_" + item.kdObat + "' class='form-control uppercase'><option value='" + item.kdObat + "'>" + item.nmObat + "<option></select></div></td>" +
                    "<td><input class='form-control' id='resep_signa1_" + item.kdObat + "' value='" + item.signa1 + "'></td>" +
                    "<td><input class='form-control' id='resep_signa2_" + item.kdObat + "' value='" + item.signa2 + "'></td>" +
                    "<td><input type='number' class='form-control' style='text-align: right;' inputmode='numeric' id='resep_jumlah_" + item.kdObat + "' value='" + item.jmlObat + "'></td>" +
                    "<td class='text-center'><button type='button' class='btn btn-sm btn-danger btn-delete-obat'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";
                $("#list-obat tbody").append(html);
            });
            rebaseObat();

            $("#title-form").text("Edit");
            $("#modal-prb").modal("show");

        });

        $("body").on("click", ".btnEditPRB", function() {
            var no_SRB = $(this).attr("id");
            var no_sep = $(this).attr("no-sep");
            MODE = "EDIT";

            //Load Detail
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "prb/sync.sh/cariprb?nosrb=" + no_SRB + "&nosep=" + no_sep,
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

                    $('#col_nosrb').fadeIn();

                    $("#txt_bpjs_prb_sep").append("<option value=\"" + data.noSEP + "\">" + data.noSEP + "</option>");
                    $("#txt_bpjs_prb_sep").select2("data", {
                        id: data.noSEP,
                        text: data.noSEP
                    });
                    $("#txt_bpjs_prb_sep").prop("disabled", true);
                    $("#txt_bpjs_prb_sep").trigger("change");

                    $("#txt_bpjs_prb_nosrb").val(data.noSRB);
                    $("#txt_bpjs_prb_nama").val(data.peserta.nama);
                    $("#txt_bpjs_prb_nokartu").val(data.peserta.noKartu);
                    $("#txt_bpjs_prb_tgllahir").val(data.peserta.tglLahir);

                    $("#switch_nmtgl").text('Tgl. SRB');
                    $("#txt_bpjs_prb_tgl_sep").val(data.tglSRB);
                    $("#txt_bpjs_prb_email").val(data.peserta.email);
                    $("#txt_bpjs_prb_alamat").val(data.peserta.alamat);

                    loadProgramPRB("#txt_bpjs_prb_program", data.programPRB.kode);
                    $("#txt_bpjs_prb_program").prop("disabled", true);
                    $("#txt_bpjs_prb_program").trigger("change");

                    $("#txt_bpjs_prb_dpjp").append("<option value='" + data.DPJP.kode + "'>" + data.DPJP.nama + "</option>");
                    $("#txt_bpjs_prb_dpjp").select2("data", {
                        id: data.DPJP.kode,
                        text: data.DPJP.nama
                    });
                    $("#txt_bpjs_prb_dpjp option[value=\"" + data.DPJP.kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_prb_dpjp").trigger("change");

                    $("#txt_bpjs_prb_keterangan").val(data.keterangan);
                    $("#txt_bpjs_prb_saran").val(data.saran);

                    $("#list-obat tbody tr").remove();
                    data.obat.obat.forEach((item) => {
                        html = "<tr id='row_" + item.kdObat + "'>" +
                            "<td><div id='col_resep_obat_" + item.kdObat + "'><select id='resep_obat_" + item.kdObat + "' class='form-control uppercase'><option value='" + item.kdObat + "'>" + item.nmObat + "<option></select></div></td>" +
                            "<td><input class='form-control' id='resep_signa1_" + item.kdObat + "' value='" + item.signa1 + "'></td>" +
                            "<td><input class='form-control' id='resep_signa2_" + item.kdObat + "' value='" + item.signa2 + "'></td>" +
                            "<td><input type='number' class='form-control' style='text-align: right;' inputmode='numeric' id='resep_jumlah_" + item.kdObat + "' value='" + item.jmlObat + "'></td>" +
                            "<td class='text-center'><button type='button' class='btn btn-sm btn-danger btn-delete-obat'><i class='fas fa-trash'></i></button></td>" +
                            "</tr>";
                        $("#list-obat tbody").append(html);
                    });
                    rebaseObat();

                    $("#title-form").text("Edit");
                    $("#modal-prb").modal("show");

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        $('#col_nosrb').hide();

        $("#btnTambahPRB").click(function() {
            MODE = "ADD";

            $("#txt_bpjs_prb_sep").focus();
            $("#txt_bpjs_prb_sep option").remove();
            $("#txt_bpjs_prb_sep").prop("disabled", false);
            $("#txt_bpjs_prb_sep").trigger("change");

            $('#col_nosrb').hide();

            $("#txt_bpjs_prb_nama").val('');
            $("#txt_bpjs_prb_nokartu").val('');
            $("#txt_bpjs_prb_tgllahir").val('');

            $("#switch_nmtgl").text('Tgl. SEP');
            $("#txt_bpjs_prb_tgl_sep").val('');
            $("#txt_bpjs_prb_email").val('');
            $("#txt_bpjs_prb_alamat").val('');

            $("#txt_bpjs_prb_keterangan").val('');
            $("#txt_bpjs_prb_saran").val('');

            $("#txt_bpjs_prb_dpjp option").remove();

            loadProgramPRB("#txt_bpjs_prb_program");
            $("#txt_bpjs_prb_program").prop("disabled", false);
            $("#txt_bpjs_prb_program").trigger("change");
            loadSpesialistik("#txt_bpjs_prb_spesialistik_dpjp");

            $("#list-obat tbody tr").remove();
            initRowAdd();

            $("#title-form").text("Baru");
            $("#modal-prb").modal("show");
        });

        $("#txt_bpjs_prb_sep").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#col_no_sep'),
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
                    $("#txt_bpjs_prb_sep option").remove();
                    if (response.metadata.code !== 200) {
                        return [];
                    } else {
                        var data = [response.response];
                        return {
                            results: $.map(data, function() {
                                return {
                                    text: data[0].noSep,
                                    id: data[0].noSep,
                                    tglSep: data[0].tglSep,
                                    noKartu: data[0].peserta.noKartu,
                                    nama: data[0].peserta.nama,
                                    tglLahir: data[0].peserta.tglLahir,
                                }
                            })
                        };
                    }
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_bpjs_prb_nama").val(data.nama);
            $("#txt_bpjs_prb_nokartu").val(data.noKartu);
            $("#txt_bpjs_prb_tgllahir").val(data.tglLahir);
            $("#txt_bpjs_prb_tgl_sep").val(data.tglSep);
        });

        $("#txt_bpjs_prb_program").select2({
            "language": {
                "noResults": function() {
                    return "Program PRB tidak ditemukan";
                }
            },
            dropdownParent: $("#col_program_prb")
        });

        loadProgramPRB("#txt_bpjs_prb_program");

        function loadProgramPRB(target, selected) {
            $('#loader-search-programprb').attr('hidden', false);
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getdiagnosaprb`,
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
                success: function(response) {
                    $('#loader-search-programprb').attr('hidden', true);
                    $(target + " option").remove();
                    if (response.metadata.code !== 200) {
                        return [];
                    } else {
                        var data = response.response;
                        for (var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");
                            if (data[a].kode === selected) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#txt_bpjs_prb_jenis_layan_dpjp").select2().on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_prb_dpjp");
        });

        $("#txt_bpjs_prb_jenis_layan_dpjp").change(function() {
            loadDPJPSpesialis("#txt_bpjs_prb_dpjp");
            $("txt_bpjs_prb_dpjp option").remove();
        });

        $("#txt_bpjs_prb_spesialistik_dpjp").select2().on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_prb_dpjp");
        });

        $("#txt_bpjs_prb_spesialistik_dpjp").select2({
            "language": {
                "noResults": function() {
                    return "Spesialistik tidak ditemukan";
                }
            },
            dropdownParent: $("#col_spesialistik")
        });

        loadSpesialistik("#txt_bpjs_prb_spesialistik_dpjp");

        function loadSpesialistik(target, selected) {
            $('#loader-search-spesialistik').attr('hidden', false);
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getspesialis`,
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
                    $('#loader-search-spesialistik').attr('hidden', true);
                    $(target + " option").remove();
                    if (response.metadata.code !== 200) {
                        return [];
                    } else {
                        var data = response.response;
                        for (var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");
                            if (data[a].kode === selected) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }
                        if (MODE === "ADD") {
                            loadDPJPSpesialis("#txt_bpjs_prb_dpjp");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#txt_bpjs_prb_dpjp").select2({
            "language": {
                "noResults": function() {
                    return "DPJP tidak ditemukan";
                }
            },
            dropdownParent: $('#col_dpjp')
        });

        function loadDPJPSpesialis(target, selected) {
            $('#loader-search-dpjp').attr('hidden', false);
            var dateNow = new Date();
            var tglSekarang = dateNow.getFullYear() + "-" + str_pad(2, dateNow.getMonth() + 1) + "-" + str_pad(2, dateNow.getDate());

            $.ajax({
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getdokter?jnspelayanan=" + $("#txt_bpjs_prb_jenis_layan_dpjp option:selected").val() + "&tglpelayanan=" + tglSekarang + "&kode=" + $("#txt_bpjs_prb_spesialistik_dpjp option:selected").val(),
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
                    $('#loader-search-dpjp').attr('hidden', true);
                    $(target + " option").remove();
                    if (response.metadata.code !== 200) {
                        return [];
                    } else {
                        var data = response.response;
                        for (var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");
                            if (data[a].kode === selected) {
                                $(selection).attr({
                                    "selected": "selected"
                                });
                            }
                            $(selection).attr("value", data[a].kode).html(data[a].nama);
                            $(target).append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function initRowAdd() {
            html = "<tr id='row_1'>" +
                "<td><div id='col_resep_obat_1'><select id='resep_obat_1' class='form-control uppercase'></select></div></td>" +
                "<td><input class='form-control' id='resep_signa1_1'></td>" +
                "<td><input class='form-control' id='resep_signa2_1'></td>" +
                "<td><input type='number' class='form-control' style='text-align: right;' inputmode='numeric' id='resep_jumlah_1'></td>" +
                "<td class='text-center'><button type='button' class='btn btn-sm btn-danger btn-delete-obat'><i class='fas fa-trash'></i></button></td>" +
                "</tr>";
            $("#list-obat tbody").append(html);
            rebaseObat();
        }

        var rowNumber = 2;
        $("body").on("click", "#addRow", function() {
            html = "<tr id='row_" + rowNumber + "'>" +
                "<td><div id='col_resep_obat_" + rowNumber + "'><select id='resep_obat_" + rowNumber + "' class='form-control uppercase'></select></div></td>" +
                "<td><input class='form-control' id='resep_signa1_" + rowNumber + "'></td>" +
                "<td><input class='form-control' id='resep_signa2_" + rowNumber + "'></td>" +
                "<td><input type='number' class='form-control' style='text-align: right;' inputmode='numeric' id='resep_jumlah_" + rowNumber + "'></td>" +
                "<td class='text-center'><button type='button' class='btn btn-sm btn-danger btn-delete-obat'><i class='fas fa-trash'></i></button></td>" +
                "</tr>";
            $("#list-obat tbody").append(html);

            rowNumber++;
            rebaseObat();
            return false;
        });

        $("#list-obat tbody").on('click', '.btn-delete-obat', function() {
            $(this).parent().parent().remove();
        });

        function rebaseObat() {
            $("#list-obat tbody tr").each(function(e) {

                var idObat = $(this).find("td:eq(0) select").attr("id");

                $('#' + idObat).addClass("form-control").select2({
                    minimumInputLength: 2,
                    language: {
                        noResults: function() {
                            return "Obat tidak ditemukan";
                        }
                    },
                    dropdownParent: $('#col_' + idObat),
                    ajax: {
                        url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getobatprb`,
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
                                namaobat: term.term
                            };
                        },
                        processResults: function(response) {
                            if (response.response !== undefined) {
                                var data = response.response;
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            text: item.nama + " - " + item.kode,
                                            id: item.kode
                                        }
                                    })
                                };
                            } else {
                                return [];
                            }
                        }
                    }
                }).on("select2:select", function(e) {
                    var data = e.params.data;
                });

            });
        }

        $("#btnProsesPRB").click(function() {
            var obatList = [];

            $("#list-obat tbody tr").each(function(e) {
                var obat = $(this).find("td:eq(0) select option:selected").val();
                var namaObat = $(this).find("td:eq(0) select option:selected").text();
                var signa1 = $(this).find("td:eq(1) input").val();
                var signa2 = $(this).find("td:eq(2) input").val();
                var jumlah = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
                obatList.push({
                    kdObat: obat,
                    signa1: signa1,
                    signa2: signa2,
                    jmlObat: jumlah
                });
            });

            if (MODE === "ADD") {
                Swal.fire({
                    title: "BPJS PRB",
                    text: "Buat PRB baru?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: `${__BPJS_SERVICE_URL__}prb/sync.sh/insertprb`,
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
                                    "t_prb": {
                                        "noSep": $("#txt_bpjs_prb_sep").val(),
                                        "noKartu": $("#txt_bpjs_prb_nokartu").val(),
                                        "alamat": $("#txt_bpjs_prb_alamat").val(),
                                        "email": $("#txt_bpjs_prb_email").val(),
                                        "programPRB": $("#txt_bpjs_prb_program").val(),
                                        "kodeDPJP": $("#txt_bpjs_prb_dpjp").val(),
                                        "keterangan": $("#txt_bpjs_prb_keterangan").val(),
                                        "saran": $("#txt_bpjs_prb_saran").val(),
                                        "user": "0069R035",
                                        "obat": obatList
                                    }
                                }
                            }),
                            success: function(response) {
                                if (parseInt(response.metadata.code) === 200) {
                                    Swal.fire(
                                        "Pembuatan PRB Berhasil!",
                                        "PRB telah dibuat",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-prb").modal("hide");
                                        DataPRB.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal buat PRB",
                                        response.metadata.message,
                                        "warning"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            },
                            error: function(response) {
                                Swal.fire(
                                    "PRB",
                                    'Aksi Gagal',
                                    "warning"
                                ).then((result) => {
                                    //
                                });
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: "BPJS PRB",
                    text: "Buat PRB edit?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `${__BPJS_SERVICE_URL__}prb/sync.sh/updateprb`,
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
                                    "t_prb": {
                                        "noSrb": $('#txt_bpjs_prb_nosrb').val(),
                                        "noSep": $("#txt_bpjs_prb_sep").val(),
                                        "alamat": $("#txt_bpjs_prb_alamat").val(),
                                        "email": $("#txt_bpjs_prb_email").val(),
                                        "kodeDPJP": $("#txt_bpjs_prb_dpjp").val(),
                                        "keterangan": $("#txt_bpjs_prb_keterangan").val(),
                                        "saran": $("#txt_bpjs_prb_saran").val(),
                                        "user": "0069R035",
                                        "obat": obatList
                                    }
                                }
                            }),
                            success: function(response) {
                                if (parseInt(response.metadata.code) === 200) {
                                    Swal.fire(
                                        "BPJS PRB",
                                        "PRB Berhasil diedit!",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-prb").modal("hide");
                                        DataPRB.ajax.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        "Gagal edit PRB",
                                        response.metadata.message,
                                        "warning"
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
            }

        });
    });
</script>




<div id="modal-prb" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Program Rujuk Balik <code id="title-form">Baru</code>
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
                                <div class="col-6">
                                    <div class="col-12 form-group" id="col_nosrb">
                                        <label for="">No. SRB</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_prb_nosrb" readonly>
                                    </div>
                                    <div class="col-12 form-group" id="col_no_sep">
                                        <label for="">No. SEP</label>
                                        <select id="txt_bpjs_prb_sep" class="form-control uppercase"></select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Nama</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_prb_nama" readonly>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">No. Kartu</label>
                                        <input type="text" id="txt_bpjs_prb_nokartu" autocomplete="off" class="form-control uppercase" readonly />
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Tgl. Lahir</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_prb_tgllahir" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 form-group">
                                        <label for="" id="switch_nmtgl">Tgl. SEP</label>
                                        <input type="text" id="txt_bpjs_prb_tgl_sep" class="form-control uppercase" readonly>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Email</label>
                                        <input type="email" autocomplete="off" class="form-control" id="txt_bpjs_prb_email" />
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Alamat</label>
                                        <textarea class="form-control" id="txt_bpjs_prb_alamat" style="min-height: 120px;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi PRB</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-6">
                                    <div class="col-12 form-group" id="col_program_prb">
                                        <div class="col-12 row">
                                            <label for="">Program PRB</label>
                                            <div class="loader loader-lg loader-primary ml-2" id="loader-search-programprb" hidden></div>
                                        </div>
                                        <select class="form-control uppercase" id="txt_bpjs_prb_program"></select>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Jenis Pelayanan</label>
                                        <select class="form-control uppercase" id="txt_bpjs_prb_jenis_layan_dpjp">
                                            <option value="1">Rawat Inap</option>
                                            <option value="2">Rawat Jalan</option>
                                        </select>
                                    </div>
                                    <div class="col-12 form-group" id="col_spesialistik">
                                        <div class="col-12 row">
                                            <label for="">Spesialistik</label>
                                            <div class="loader loader-lg loader-primary ml-2" id="loader-search-spesialistik" hidden></div>
                                        </div>
                                        <select class="form-control uppercase" id="txt_bpjs_prb_spesialistik_dpjp"></select>
                                    </div>
                                    <div class="col-12 form-group" id="col_dpjp">
                                        <div class="col-12 row">
                                            <label for="">DPJP</label>
                                            <div class="loader loader-lg loader-primary ml-2" id="loader-search-dpjp" hidden></div>
                                        </div>
                                        <select class="form-control uppercase" id="txt_bpjs_prb_dpjp"></select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="col-12 form-group">
                                        <label for="">Keterangan</label>
                                        <textarea class="form-control" style="min-height: 125px;" id="txt_bpjs_prb_keterangan"></textarea>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label for="">Saran</label>
                                        <textarea class="form-control" style="min-height: 125px;" id="txt_bpjs_prb_saran"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Obat</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-12">
                                    <table class="table largeDataType" id="list-obat">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Obat</th>
                                                <th style="width: 10%;">Signa 1</th>
                                                <th style="width: 10%;">Signa 2</th>
                                                <th style="width: 10%;">Jumlah</th>
                                                <th style="width: 10%;" class="text-center"><button type='button' id='addRow' class='btn btn-sm btn-success'><i class='fas fa-plus'></i> Add Row</button></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesPRB">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>