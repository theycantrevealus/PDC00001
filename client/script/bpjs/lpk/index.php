<script type="text/javascript">
    $(function() {

        var dataEditLPK = [];

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
            // serverSide: true,
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
                        dataEditLPK = response.response;
                        return response.response;
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
                        return row.peserta.nama + "[No.Mr " + row.peserta.noMR + "]";
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
                            "<button class=\"btn btn-info btn-sm bpjs_edit_lpk\" index=\"" + LpkList.data().count() + "\" id=\"" + row.noSep + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger bpjs_hapus_lpk\" id=\"" + row.noSep + "\"><i class=\"fa fa-trash\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });


        $("body").on("click", ".bpjs_hapus_lpk", function() {
            var no_sep = $(this).attr("id");
            var btn_proses = $(this);
            Swal.fire({
                title: "BPJS Hapus LPK",
                title: "Hapus LPK No.SEP " + noSep + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    btn_proses.html('Proses..').attr('disabled', true);

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
                            request.setRequestHeader("Content-Type", "application/json");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: JSON.stringify({
                            "request": {
                                "t_lpk": {
                                    "noSep": no_sep
                                }
                            }
                        }),
                        success: function(response) {
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    "BPJS Hapus LPK",
                                    "LPK Berhasil dihapus!",
                                    "success"
                                ).then((result) => {
                                    LpkList.ajax.reload();
                                    btn_proses.html('<i class=\"fa fa-trash\"></i> Hapus').attr('disabled', false);
                                });
                            } else {
                                Swal.fire(
                                    "BPJS Hapus LPK",
                                    response.metadata.message,
                                    "error"
                                );
                                btn_proses.html('<i class=\"fa fa-trash\"></i> Hapus').attr('disabled', false);
                            }

                        },
                        error: function(response) {
                            Swal.fire(
                                "BPJS Hapus LPK",
                                'Aksi Gagal',
                                "error"
                            );
                            btn_proses.html('<i class=\"fa fa-trash\"></i> Hapus').attr('disabled', false);
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", "#btnTestEdit", function() {
            MODE = "EDIT";

            var dataLPK = [{
                "DPJP": {
                    "dokter": {
                        "kode": "3",
                        "nama": "Satro Jadhit, dr"
                    }
                },
                "diagnosa": {
                    "list": [{
                            "level": "1",
                            "list": {
                                "kode": "N88.1",
                                "nama": "Old laceration of cervix uteri"
                            }
                        },
                        {
                            "level": "2",
                            "list": {
                                "kode": "A00.1",
                                "nama": "Cholera due to Vibrio cholerae 01, biovar eltor"
                            }
                        }
                    ]
                },
                "jnsPelayanan": "1",
                "noSep": "0301R0011017V000014",
                "perawatan": {
                    "caraKeluar": {
                        "kode": "1",
                        "nama": "Atas Persetujuan Dokter"
                    },
                    "kelasRawat": {
                        "kode": "1",
                        "nama": "VVIP"
                    },
                    "kondisiPulang": {
                        "kode": "1",
                        "nama": "Sembuh"
                    },
                    "ruangRawat": {
                        "kode": "3",
                        "nama": "Ruang Melati I"
                    },
                    "spesialistik": {
                        "kode": "1",
                        "nama": "Spesialis Penyakit dalam"
                    }
                },
                "peserta": {
                    "kelamin": "L",
                    "nama": "Example",
                    "noKartu": "0000000001231",
                    "noMR": "123456",
                    "tglLahir": "2008-02-05"
                },
                "poli": {
                    "eksekutif": "0",
                    "poli": {
                        "kode": "INT"
                    }
                },
                "procedure": {
                    "list": [{
                            "list": {
                                "kode": "00.82",
                                "nama": "Revision of knee replacement, femoral component"
                            }
                        },
                        {
                            "list": {
                                "kode": "00.83",
                                "nama": "Revision of knee replacement,patellar component"
                            }
                        }
                    ]
                },
                "rencanaTL": null,
                "tglKeluar": "2017-10-30",
                "tglMasuk": "2017-10-30"
            }];

            var index_data = parseInt(1) - 1;
            var LpkDetail = dataLPK[index_data];

            $("#title-form").text('Edit');

            $("#txt_bpjs_lpk_no_sep").append("<option value=\"" + LpkDetail.noSep + "\">" + LpkDetail.noSep + "</option>");
            $("#txt_bpjs_lpk_no_sep").select2("data", {
                id: LpkDetail.noSep,
                text: LpkDetail.noSep
            });
            $("#txt_bpjs_lpk_no_sep").prop("disabled", true);
            $("#txt_bpjs_lpk_no_sep").trigger("change");

            $("#txt_nama_lpk_new").val(LpkDetail.peserta.nama);
            $("#txt_nokartu_lpk_new").val(LpkDetail.peserta.noKartu);
            $("#txt_tgllahir_lpk_new").val(LpkDetail.peserta.tglLahir);
            // $("#txt_tglsep_lpk_new").val();

            $("#txt_bpjs_lpk_tgl_masuk").val(LpkDetail.tglMasuk);
            $("#txt_bpjs_lpk_tgl_keluar").val(LpkDetail.tglKeluar);

            $("#txt_bpjs_lpk_jenis_layan option[value=\"" + LpkDetail.jnsPelayanan + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_jenis_layan").trigger("change");
            $("#txt_bpjs_lpk_jenis_layan").prop("disabled", true);

            if (LpkDetail.jnsPelayanan == 1) {
                loadRuangRawat("#txt_bpjs_lpk_ruang_rawat", LpkDetail.perawatan.ruangRawat.kode);
                loadKelasRawat("#txt_bpjs_lpk_kelas_rawat", LpkDetail.perawatan.kelasRawat.kode);
                loadSpesialistikLpk("#txt_bpjs_lpk_spesialistik", LpkDetail.perawatan.spesialistik.kode);
                loadCaraKeluar("#txt_bpjs_lpk_cara_keluar", LpkDetail.perawatan.caraKeluar.kode);
                loadKondisiPulang("#txt_bpjs_lpk_kondisi_pulang", LpkDetail.perawatan.kondisiPulang.kode);
            }


            $("#txt_bpjs_lpk_poli").append("<option>" + LpkDetail.poli.poli.kode + "</option>");
            $("#txt_bpjs_lpk_poli").select2("data", {
                id: LpkDetail.poli.poli.kode,
                text: LpkDetail.poli.poli.kode
            });
            $("#txt_bpjs_lpk_poli").trigger("change");

            $("#txt_bpjs_lpk_dpjp").append("<option>" + LpkDetail.DPJP.dokter.nama + "</option>");
            $("#txt_bpjs_lpk_dpjp").select2("data", {
                id: LpkDetail.DPJP.dokter.kode,
                text: LpkDetail.DPJP.dokter.nama
            });
            $("#txt_bpjs_lpk_dpjp").trigger("change");


            LpkDetail.diagnosa.list.forEach((item) => {
                html = "<tr>" +
                    "<td>" + item.list.kode + "</td>" +
                    "<td>" + item.list.nama + "</td>" +
                    "<td>" + item.level + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-diagnosa'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-diagnosa tbody").append(html);
            });

            LpkDetail.procedure.list.forEach((item) => {
                html = "<tr>" +
                    "<td>" + item.list.kode + "</td>" +
                    "<td>" + item.list.nama + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-procedure'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-procedure tbody").append(html);
            });

            $("#txt_bpjs_lpk_tindak_lanjut option[value=\"" + LpkDetail.rencanaTL + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_tindak_lanjut").trigger("change");

            $("#modal-lpk-bpjs").modal("show");

        });

        $("body").on("click", ".bpjs_edit_lpk", function() {
            MODE = "EDIT";
            var btn_proses = $(this);
            btn_proses.html('Proses..').attr('disabled', true);

            var index = $(this).attr("index");
            var index_data = parseInt(index) - 1;
            var LpkDetail = dataEditLPK[index_data];

            $("#title-form").text('Edit');

            $("#txt_bpjs_lpk_no_sep").append("<option value=\"" + LpkDetail.noSep + "\">" + LpkDetail.noSep + "</option>");
            $("#txt_bpjs_lpk_no_sep").select2("data", {
                id: LpkDetail.noSep,
                text: LpkDetail.noSep
            });
            $("#txt_bpjs_lpk_no_sep").prop("disabled", true);
            $("#txt_bpjs_lpk_no_sep").trigger("change");

            $("#txt_nama_lpk_new").val(LpkDetail.peserta.nama);
            $("#txt_nokartu_lpk_new").val(LpkDetail.peserta.noKartu);
            $("#txt_tgllahir_lpk_new").val(LpkDetail.peserta.tglLahir);
            // $("#txt_tglsep_lpk_new").val();

            $("#txt_bpjs_lpk_tgl_masuk").val(LpkDetail.tglMasuk);
            $("#txt_bpjs_lpk_tgl_keluar").val(LpkDetail.tglKeluar);

            $("#txt_bpjs_lpk_jenis_layan option[value=\"" + LpkDetail.jnsPelayanan + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_jenis_layan").trigger("change");
            $("#txt_bpjs_lpk_jenis_layan").prop("disabled", true);

            if (LpkDetail.jnsPelayanan !== 2) {
                loadRuangRawat("#txt_bpjs_lpk_ruang_rawat", LpkDetail.perawatan.ruangRawat.kode);
                loadKelasRawat("#txt_bpjs_lpk_kelas_rawat", LpkDetail.perawatan.kelasRawat.kode);
                loadSpesialistikLpk("#txt_bpjs_lpk_spesialistik", LpkDetail.perawatan.spesialistik.kode);
                loadCaraKeluar("#txt_bpjs_lpk_cara_keluar", LpkDetail.perawatan.caraKeluar.kode);
                loadKondisiPulang("#txt_bpjs_lpk_kondisi_pulang", LpkDetail.perawatan.kondisiPulang.kode);
            }


            $("#txt_bpjs_lpk_poli").append("<option>" + LpkDetail.poli.poli.kode + "</option>");
            $("#txt_bpjs_lpk_poli").select2("data", {
                id: LpkDetail.poli.poli.kode,
                text: LpkDetail.poli.poli.kode
            });
            $("#txt_bpjs_lpk_poli").trigger("change");

            $("#txt_bpjs_lpk_dpjp").append("<option>" + LpkDetail.DPJP.dokter.nama + "</option>");
            $("#txt_bpjs_lpk_dpjp").select2("data", {
                id: LpkDetail.DPJP.dokter.kode,
                text: LpkDetail.DPJP.dokter.nama
            });
            $("#txt_bpjs_lpk_dpjp").trigger("change");


            LpkDetail.diagnosa.list.forEach((item) => {
                html = "<tr>" +
                    "<td>" + item.list.kode + "</td>" +
                    "<td>" + item.list.nama + "</td>" +
                    "<td>" + item.level + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-diagnosa'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-diagnosa tbody").append(html);
            });

            LpkDetail.procedure.list.forEach((item) => {
                html = "<tr>" +
                    "<td>" + item.list.kode + "</td>" +
                    "<td>" + item.list.nama + "</td>" +
                    "<td><button type='button' class='btn btn-sm btn-danger btn-delete-procedure'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>";

                $("#list-procedure tbody").append(html);
            });

            $("#txt_bpjs_lpk_tindak_lanjut option[value=\"" + LpkDetail.rencanaTL + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_tindak_lanjut").trigger("change");

            btn_proses.html('<i class=\"fa fa-pencil-alt\"></i> Edit').attr('disabled', false);
            $("#modal-lpk-bpjs").modal("show");

        });


        /////////ADD ZONE/////////

        $("#btnTambahLPK").click(function() {
            resetForm();
            $("#title-form").text('Baru');
            $("#modal-lpk-bpjs").modal("show");
            MODE = "ADD";
        });

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
            dropdownParent: $("#col_nosep"),
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
                    if (parseInt(response.metadata.code) !== 200) {
                        $("#txt_bpjs_lpk_no_sep option").remove();
                        return [];
                    } else {
                        var data = [response.response];
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: data[0].noSep,
                                    id: data[0].noSep,
                                    tglSep: data[0].tglSep,
                                    noKartu: data[0].peserta.noKartu,
                                    nama: data[0].peserta.nama,
                                    tglLahir: data[0].peserta.tglLahir,
                                    jnsPelayanan: data[0].jnsPelayanan
                                }
                            })
                        };
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;
            $("#txt_nama_lpk_new").val(data.nama);
            $("#txt_nokartu_lpk_new").val(data.noKartu);
            $("#txt_tgllahir_lpk_new").val(data.tglLahir);
            $("#txt_tglsep_lpk_new").val(data.tglSep);
            $('#txt_bpjs_lpk_tgl_masuk').val(data.tglSep);
            $('#txt_bpjs_lpk_tgl_keluar').val(data.tglSep);

            if (data.jnsPelayanan === "Rawat Jalan") {
                var jns_layan = "2";
            } else {
                var jns_layan = "1";

                loadRuangRawat("#txt_bpjs_lpk_ruang_rawat");
                loadKelasRawat("#txt_bpjs_lpk_kelas_rawat");
                loadSpesialistikLpk("#txt_bpjs_lpk_spesialistik");
                loadCaraKeluar("#txt_bpjs_lpk_cara_keluar");
                loadKondisiPulang("#txt_bpjs_lpk_kondisi_pulang");
            }
            $("#txt_bpjs_lpk_jenis_layan option[value=\"" + jns_layan + "\"]").prop("selected", true);
            $("#txt_bpjs_lpk_jenis_layan").trigger("change");
            $("#txt_bpjs_lpk_jenis_layan").prop("disabled", true);
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

        $("#txt_bpjs_lpk_jaminan").select2();

        $("#txt_bpjs_lpk_ruang_rawat").select2({
            "language": {
                "noResults": function() {
                    return "Ruang rawat tidak ditemukan";
                }
            },
            dropdownParent: $("#group_ruang_rawat")
        });

        function loadRuangRawat(target, selected) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getruangrawat`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
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

        $("#txt_bpjs_lpk_kelas_rawat").select2({
            "language": {
                "noResults": function() {
                    return "Kelas rawat tidak ditemukan";
                }
            },
            dropdownParent: $("#group_kelas_rawat")
        });

        function loadKelasRawat(target, selected) {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}ref/sync.sh/getkelasrawat`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
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

        $("#txt_bpjs_lpk_spesialistik").select2({
            "language": {
                "noResults": function() {
                    return "Spesialistik tidak ditemukan";
                }
            },
            dropdownParent: $("#group_spesialistik")
        });


        function loadSpesialistikLpk(target, selected) {
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
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
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

        $("#txt_bpjs_lpk_cara_keluar").select2({
            "language": {
                "noResults": function() {
                    return "Cara Keluar tidak ditemukan";
                }
            },
            dropdownParent: $("#group_cara_keluar")
        });


        function loadCaraKeluar(target, selected) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getcarakeluar",
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
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

        $("#txt_bpjs_lpk_kondisi_pulang").select2({
            "language": {
                "noResults": function() {
                    return "Kondisi Pulang tidak ditemukan";
                }
            },
            dropdownParent: $("#group_kondisi_pulang")
        });


        function loadKondisiPulang(target, selected) {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "ref/sync.sh/getpascapulang",
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                success: function(response) {
                    $(target + " option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
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
                    $("#txt_bpjs_lpk_poli option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
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

        $("#txt_bpjs_lpk_dpjp").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "DPJP tidak ditemukan";
                }
            },
            dropdownParent: $("#group_dpjp"),
            ajax: {
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
                    $("#txt_bpjs_lpk_dpjp option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
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
                    $("#txt_bpjs_lpk_procedure option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
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
                    $("#txt_bpjs_lpk_diagnosa option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
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

        $("#txt_bpjs_lpk_tindak_lanjut").select2();
        $("#txt_bpjs_lpk_dirujukke_jenis_faskes").select2();

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
                    $("#txt_bpjs_lpk_dirujukke_faskes option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
                    } else {
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
                    $("#txt_bpjs_lpk_poli_kontrol_kembali option").remove();
                    if (parseInt(response.metadata.code) !== 200) {
                        return [];
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

        function resetForm() {
            $("#txt_bpjs_lpk_jenis_layan").prop("disabled", false);

            $('#txt_bpjs_lpk_no_sep').prop('disabled', false);
            $('#txt_bpjs_lpk_no_sep option').remove();
            $('#txt_nama_lpk_new').val('');
            $('#txt_nokartu_lpk_new').val('');
            $('#txt_tgllahir_lpk_new').val('');
            $('#txt_tglsep_lpk_new').val('');

            $('#txt_bpjs_lpk_ruang_rawat option').remove();
            $('#txt_bpjs_lpk_kelas_rawat option').remove();
            $('#txt_bpjs_lpk_spesialistik option').remove();
            $('#txt_bpjs_lpk_cara_keluar option').remove();
            $('#txt_bpjs_lpk_kondisi_pulang option').remove();

            $('#txt_bpjs_lpk_poli option').remove();
            $('#txt_bpjs_lpk_dpjp option').remove();

            $("#list-diagnosa tbody tr").remove();
            $("#list-procedure tbody tr").remove();

            $('#txt_bpjs_lpk_dirujukke_faskes option').remove();
            $('#txt_bpjs_lpk_poli_kontrol_kembali option').remove();
        }

        $("body").on("click", "#btnProsesLpk", function() {
            var btn_proses = $(this);
            console.log(MODE);
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

            if (MODE === "ADD") {
                Swal.fire({
                    title: "Data Sudah Benar?",
                    text: "Proses BPJS LPK?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn_proses.html('Proses..').attr('disabled', true);

                        if ($('#txt_bpjs_lpk_no_sep').val() !== null) {
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
                                    request.setRequestHeader("Content-Type", "application/json");
                                    request.setRequestHeader("x-token", bpjs_token);
                                },
                                "data": JSON.stringify({
                                    "request": {
                                        "t_lpk": {
                                            "noSep": $('#txt_bpjs_lpk_no_sep').val(),
                                            "tglMasuk": $('#txt_bpjs_lpk_tgl_masuk').val(),
                                            "tglKeluar": $('#txt_bpjs_lpk_tgl_keluar').val(),
                                            "jaminan": $('#txt_bpjs_lpk_jaminan').val(),
                                            "poli": {
                                                "poli": $('#txt_bpjs_lpk_poli').val()
                                            },
                                            "perawatan": {
                                                "ruangRawat": $('#txt_bpjs_lpk_ruang_rawat').val(),
                                                "kelasRawat": $('#txt_bpjs_lpk_kelas_rawat').val(),
                                                "spesialistik": $('#txt_bpjs_lpk_spesialistik').val(),
                                                "caraKeluar": $('#txt_bpjs_lpk_cara_keluar').val(),
                                                "kondisiPulang": $('#txt_bpjs_lpk_kondisi_pulang').val()
                                            },
                                            "diagnosa": diagnosa_list,
                                            "procedure": procedure_list,
                                            "rencanaTL": {
                                                "tindakLanjut": $('#txt_bpjs_lpk_tindak_lanjut').val(),
                                                "dirujukKe": {
                                                    "kodePPK": ($('#txt_bpjs_lpk_dirujukke_faskes').val()) ? $('#txt_bpjs_lpk_dirujukke_faskes').val() : ""
                                                },
                                                "kontrolKembali": {
                                                    "tglKontrol": ($('#txt_bpjs_lpk_tgl_kontrol_kembali').val()) ? $('#txt_bpjs_lpk_tgl_kontrol_kembali').val() : "",
                                                    "poli": ($('#txt_bpjs_lpk_poli_kontrol_kembali').val()) ? $('#txt_bpjs_lpk_poli_kontrol_kembali').val() : ""
                                                }
                                            },
                                            "DPJP": $('#txt_bpjs_lpk_dpjp').val(),
                                            "user": __MY_NAME__
                                        }
                                    }
                                }),
                                success: function(response) {
                                    if (parseInt(response.metadata.code) === 200) {
                                        Swal.fire(
                                            'BPJS LPK',
                                            'LPK Berhasil disimpan!',
                                            'success'
                                        ).then((result) => {
                                            LpkList.ajax.reload();
                                            $("#modal-lpk-bpjs").modal("hide");
                                            btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                        });
                                    } else {
                                        Swal.fire(
                                            'BPJS LPK',
                                            response.metadata.message,
                                            'error'
                                        );
                                        btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                    }
                                },
                                error: function(response) {
                                    Swal.fire(
                                        'BPJS LPK',
                                        'Aksi Gagal',
                                        'error'
                                    );
                                    btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                    console.clear();
                                    console.log(response);
                                }
                            });
                        } else {
                            Swal.fire(
                                'BPJS LPK',
                                'No.SEP Tidak Boleh Kosong!',
                                'error'
                            );
                            btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                        }
                    }
                });

            } else if (MODE === "EDIT") {
                Swal.fire({
                    title: "Update LPK BPJS?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn_proses.html('Proses..').attr('disabled', true);

                        $.ajax({
                            url: __BPJS_SERVICE_URL__ + "lpk/sync.sh/updatelpk",
                            type: "PUT",
                            dataType: "json",
                            crossDomain: true,
                            beforeSend: async function(request) {
                                refreshToken().then((test) => {
                                    bpjs_token = test;
                                })

                                request.setRequestHeader("Accept", "application/json");
                                request.setRequestHeader("Content-Type", "application/json");
                                request.setRequestHeader("x-token", bpjs_token);
                            },
                            data: JSON.stringify({
                                "request": {
                                    "t_lpk": {
                                        "noSep": $('#txt_bpjs_lpk_no_sep').val(),
                                        "tglMasuk": $('#txt_bpjs_lpk_tgl_masuk').val(),
                                        "tglKeluar": $('#txt_bpjs_lpk_tgl_keluar').val(),
                                        "jaminan": $('#txt_bpjs_lpk_jaminan').val(),
                                        "poli": {
                                            "poli": $('#txt_bpjs_lpk_poli').val()
                                        },
                                        "perawatan": {
                                            "ruangRawat": $('#txt_bpjs_lpk_ruang_rawat').val(),
                                            "kelasRawat": $('#txt_bpjs_lpk_kelas_rawat').val(),
                                            "spesialistik": $('#txt_bpjs_lpk_spesialistik').val(),
                                            "caraKeluar": $('#txt_bpjs_lpk_cara_keluar').val(),
                                            "kondisiPulang": $('#txt_bpjs_lpk_kondisi_pulang').val()
                                        },
                                        "diagnosa": diagnosa_list,
                                        "procedure": procedure_list,
                                        "rencanaTL": {
                                            "tindakLanjut": $('#txt_bpjs_lpk_tindak_lanjut').val(),
                                            "dirujukKe": {
                                                "kodePPK": $('#txt_bpjs_lpk_dirujukke_faskes').val()
                                            },
                                            "kontrolKembali": {
                                                "tglKontrol": $('#txt_bpjs_lpk_tgl_kontrol_kembali').val(),
                                                "poli": $('#txt_bpjs_lpk_poli_kontrol_kembali').val()
                                            }
                                        },
                                        "DPJP": $('#txt_bpjs_lpk_dpjp').val(),
                                        "user": __MY_NAME__
                                    }
                                }
                            }),
                            success: function(response) {
                                if (parseInt(response.metadata.code) === 200) {
                                    Swal.fire(
                                        'BPJS Edit LPK',
                                        'Rujukan Berhasil Diubah',
                                        'success'
                                    ).then((result) => {
                                        LpkList.ajax.reload();
                                        $("#modal-lpk-bpjs-edit").modal("hide");
                                        btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                    });
                                } else {
                                    Swal.fire(
                                        'BPJS Edit LPK',
                                        response.metadata.message,
                                        'error'
                                    );
                                    btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                }
                            },
                            error: function(response) {
                                Swal.fire(
                                    'BPJS Edit LPK',
                                    'Aksi Gagal',
                                    'error'
                                );
                                btn_proses.html('<i class=\"fa fa-check\"></i> Proses').attr('disabled', false);
                                console.clear();
                                console.log(response);
                            }
                        });
                    }
                });
            }
        });

        //
    });
</script>

<div id="modal-lpk-bpjs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Lembar Pengajuan Klaim <code id="title-form">Baru</code>
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
                            <div class="col-3 form-group" id="col_nosep">
                                <label for="">No. SEP</label>
                                <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_lpk_no_sep"></select>
                            </div>
                            <div class="col-3 form-group">
                                <label for="">Nama</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nama_lpk_new" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Nomor Kartu</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_nokartu_lpk_new" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. Lahir</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tgllahir_lpk_new" readonly>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Tgl. SEP</label>
                                <input type="text" autocomplete="off" class="form-control uppercase" id="txt_tglsep_lpk_new" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi LPK</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Masuk</label>
                                    <input type="text" data-width="100%" class="form-control" id="txt_bpjs_lpk_tgl_masuk">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Tanggal Keluar</label>
                                    <input type="text" data-width="100%" class="form-control" id="txt_bpjs_lpk_tgl_keluar">
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Penjamin</label>
                                    <select data-width="100%" class="form-control" id="txt_bpjs_lpk_jaminan">
                                        <option value="1">JKN</option>
                                    </select>
                                </div>
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
                                <div class="col-12 row">
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
                                <div class="col-12 row">
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
                    <i class="fa fa-check"></i> Proses
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>