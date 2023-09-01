<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/pdfjs/pdf2.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(async function() {


        var selectedKartu = "";
        var selected_SPRI = "";

        var refreshData = 'N';
        var SPRINo = "";
        var MODE = "ADD";
        var getUrl = __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol?nosuratkontrol=0301R0010120K000003";

        $("#btn_search_no_surat").click(function() {
            getUrl = __BPJS_SERVICE_URL__ + "rc/sync.sh/carisuratkontrol/?nosuratkontrol=" + $("#search_no_surat").val();
            MODE = "SEARCH";
            DataSPRI.ajax.url(getUrl).load();
        });

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
            MODE = "SEARCH";
            DataSPRI.ajax.url(getUrl).load();
        });

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
            MODE = "SEARCH";
            DataSPRI.ajax.url(getUrl).load();
        });

        $('#alert-sprirk-container').hide();

        var DataSPRI = $("#table-spri").DataTable({
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
                            $('#alert-sprirk').text(response.metadata.message);
                            $('#alert-sprirk-container').fadeIn();
                        }
                        return [];
                    } else {
                        $('#alert-sprirk-container').fadeOut();

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
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.tglTerbit + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.tglRencanaKontrol + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (parseInt(row.jnsKontrol) === 1) ? "SPRI" : "Rencana Kontrol";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.noSuratKontrol;
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
                        return "<span class=\"wrap_content\">" + row.poliTujuan + "<br /><b class=\"text-info\">" + row.namaPoliTujuan + "</b></span>";
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
                            "<button class=\"btn btn-info btn-sm btnEditSPRI\" no-sep=\"" + row.sep.noSep + "\" id=\"" + row.noSuratKontrol + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSPRI\" id=\"" + row.noSuratKontrol + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnHapusSPRI", function() {
            var no_surat = $(this).attr("id");

            Swal.fire({
                title: "BPJS SPRI / Rencana Kontrol",
                text: "Hapus SPRI / Rencana Kontrol " + no_surat + "?",
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
                        data: {
                            "noSuratKontrol": no_surat,
                            "user": __MY_NAME__
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metaData.code) === 200) {
                                Swal.fire(
                                    'BPJS SPRI / Rencana Kontrol',
                                    'SPRI / Rencana Kontrol Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    DataSPRI.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS SPRI / Rencana Kontrol',
                                    response.metaData.message,
                                    'error'
                                ).then((result) => {
                                    DataSPRI.ajax.reload();
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

        $("body").on("click", ".btnEditSPRI", function() {
            var no_surat = $(this).attr("id");
            MODE = "EDIT";

            $("#modal-edit-spri").modal("show");

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

                    var data = response.response[0];

                    $("#txt_bpjs_spri_jenis").select2("data", {
                        id: data.jnsKontrol,
                        text: ((data.jnsKontrol == 1) ? "SPRI" : "Rencana Kontrol")
                    });
                    if (data.jnsKontrol == 1) {
                        $("#switch_jenis").html("No. Kartu");
                        $("#txt_bpjs_spri_noKartu").val(data.sep.noKartu);
                    } else {
                        $("#switch_jenis").html("No. SEP");
                        $("#txt_bpjs_spri_noKartu").val(data.sep.noSep);
                    }
                    $("#txt_bpjs_spri_tglRencanaKontrol").val(data.tglRencanaKontrol);

                    refreshSpesialistik();
                    $("#txt_bpjs_spri_poliKontrol").append("<option value=\"" + data.poliTujuan + "\">" + data.mamaPoliTujuan + "</option>");
                    $("#txt_bpjs_spri_poliKontrol").select2("data", {
                        id: data.poliTujuan,
                        text: data.mamaPoliTujuan
                    });
                    $("#txt_bpjs_spri_poliKontrol").trigger("change");

                    refreshJadwalDokter();
                    $("#txt_bpjs_spri_kodeDokter").append("<option value=\"" + data.kodeDokter + "\">" + data.mamaPoliTujuan + "</option>");
                    $("#txt_bpjs_spri_kodeDokter").select2("data", {
                        id: data.kodeDokter,
                        text: data.namaDokter
                    });
                    $("#txt_bpjs_spri_kodeDokter").trigger("change");

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        function refreshSpesialistik() {
            $.ajax({
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/listspesialistik/?jeniskontrol=" + $("#txt_bpjs_spri_jenis").val() + "&nomor=" + $("#txt_bpjs_spri_noKartu").val() + "&tglrencanakontrol=" + $("#txt_bpjs_spri_tglRencanaKontrol").val(),
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
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_spri_poliKontrol").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodePoli,
                                text: `${data[a].kodePoli} - ${data[a].namaPoli}`
                            })
                        }
                        $("#txt_bpjs_spri_poliKontrol").select2({
                            data: parsedData
                        });
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
<<<<<<< HEAD
                url: __BPJS_SERVICE_URL__ + "rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=" + $("#txt_bpjs_spri_jenis").val() + "&kodepoli=" + $("#txt_bpjs_spri_poliKontrol").val() + "&tglrencanakontrol=" + $("#txt_bpjs_spri_tglRencanaKontrol").val(),
=======
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=${$("#txt_bpjs_spri_jenis").val()}&kodepoli=${$("#txt_bpjs_spri_poliKontrol").val()}&tglrencanakontrol=${$("#txt_bpjs_spri_tglRencanaKontrol").val()}`,
>>>>>>> e767d1a8e2282a3490d78de710f14c118d984e69
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
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_spri_kodeDokter").trigger("change.select2");
                    } else {
                        var data = response.response;
                        var parsedData = []
                        for (const a in data) {
                            parsedData.push({
                                id: data[a].kodeDokter,
                                text: `${data[a].kodeDokter} - ${data[a].namaDokter} [${data[a].jadwalPraktek}]`
                            })
                        }
                        $("#txt_bpjs_spri_kodeDokter").select2({
                            data: parsedData
                        });
                    }
                },
                error: function(error) {
                    console.clear();
                    console.log(error);
                }
            });
        }

        $("#txt_bpjs_spri_noKartu").on("change", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_poliKontrol").on("select2:select", function(e) {
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_jenis").on("change", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_tglRencanaKontrol").on("change", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_tglRencanaKontrol").datepicker({
            dateFormat: "yy-mm-dd",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_spri_jenis").select2().on("select2:select", function(e) {
            refreshSpesialistik();
            refreshJadwalDokter();
            if ($("#txt_bpjs_spri_jenis").val() == 1) {
                $("#switch_jenis").html("No. Kartu");
            } else {
                $("#switch_jenis").html("No. SEP");
            }

        });

        $("#txt_bpjs_spri_poliKontrol").select2().addClass("form-control");

        $("#txt_bpjs_spri_kodeDokter").select2().addClass("form-control");


        $("#btnTambahSPRI").click(function() {
            $("#modal-spri").modal("show");
            $("#txt_bpjs_rk_pasien").removeAttr("disabled");
            $("#txt_bpjs_rk_sep").removeAttr("disabled");
            $("#txt_bpjs_rk_nomor_kartu").focus();
            MODE = "ADD";
        });

        $("#txt_bpjs_rk_spesialistik_dpjp").select2({
            dropdownParent: $('#modal-spri')
        });

        $("#txt_bpjs_rk_dpjp").select2({
            dropdownParent: $('#modal-spri')
        });


        $("#btnProsesSPRI").click(function() {
            var no_kartu = $("#txt_bpjs_spri_noKartu").val();
            var tanggal_kontrol = $("#txt_bpjs_spri_tglRencanaKontrol").val();
            var poli_kontrol = $("#txt_bpjs_spri_poliKontrol option:selected").val();
            var kode_dokter = $("#txt_bpjs_spri_kodeDokter option:selected").val();

            Swal.fire({
                title: "BPJS SPRI / Rencana Kontrol",
                text: "Buat SPRI / Rencana Kontrol baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($("#txt_bpjs_spri_jenis").val() == 1) {
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
                            data: {
                                "noKartu": no_kartu,
                                "kodeDokter": kode_dokter,
                                "poliKontrol": poli_kontrol,
                                "tglRencanaKontrol": tanggal_kontrol, //format:"2021-04-13"
                                "user": __MY_NAME__
                            },
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "SPRI",
                                        "SPRI berhasil diproses",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        DataSPRI.ajax.reload();
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
                            data: {
                                "noSEP": no_kartu,
                                "kodeDokter": kode_dokter,
                                "poliKontrol": poli_kontrol,
                                "tglRencanaKontrol": tanggal_kontrol, //format:"2021-04-13"
                                "user": __MY_NAME__
                            },
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "Rencana Kontrol",
                                        "Rencana Kontrol berhasil diproses",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        DataSPRI.ajax.reload();
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
                            }
                        });
                    }
                }
            });
        });

        $("#btnEditSPRI").click(function() {
            var no_kartu = $("#txt_bpjs_spri_noKartu").val();
            var tanggal_kontrol = $("#txt_bpjs_spri_tglRencanaKontrol").val();
            var poli_kontrol = $("#txt_bpjs_spri_poliKontrol option:selected").val();
            var kode_dokter = $("#txt_bpjs_spri_kodeDokter option:selected").val();

            Swal.fire({
                title: "BPJS SPRI / Rencana Kontrol",
                text: "Buat SPRI / Rencana Kontrol edit?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    if ($("#txt_bpjs_spri_jenis").val() == 1) {
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
                            data: {
                                "noSPRI": no_kartu,
                                "kodeDokter": kode_dokter,
                                "poliKontrol": poli_kontrol,
                                "tglRencanaKontrol": tanggal_kontrol, //format:"2021-04-13"
                                "user": __MY_NAME__
                            },
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "SPRI",
                                        "SPRI berhasil diedit",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        DataSPRI.ajax.reload();
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
                            data: {
                                "noSEP": no_kartu,
                                "kodeDokter": kode_dokter,
                                "poliKontrol": poli_kontrol,
                                "tglRencanaKontrol": tanggal_kontrol, //format:"2021-04-13"
                                "user": __MY_NAME__
                            },
                            success: function(response) {
                                if (parseInt(response.metaData.code) === 200) {
                                    Swal.fire(
                                        "Rencana Kontrol",
                                        "Rencana Kontrol berhasil diedit",
                                        "success"
                                    ).then((result) => {
                                        $("#modal-spri").modal("hide");
                                        DataSPRI.ajax.reload();
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
                            }
                        });
                    }
                }
            });
        });
    });

    $("body").on("click", "#btnCetakRkTest", function() {
        $("#title-surat").text("Surat Perintah Rawat Inap");
        $("#rk_dokter").html("DR.dr.H Eva Decroli, SpPD K-EMD Finasim<br>ENDOKRIN-METABOLIK-DIABETES");
        $("#rk_nomor_kartu").html("OOOBO154504O1");
        $("#rk_nama_pasien").html("PIASDIL (L)");
        $("#rk_tanggal_lahir").html("14 Agustus 1999");
        $("#rk_diagnosa_awal").html("EI1 - Non-insulin-dependent diabetes mellitus");
        $("#rk_tanggal_terbit").html("14 Agustus 2023");

        var dateNow = new Date();
        var tgl_cetak = str_pad(2, dateNow.getDate()) + "/" + str_pad(2, dateNow.getMonth() + 1) + "/" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
        $("#tgl_cetak").html("Tgl. Cetak " + tgl_cetak);

        $("#rk_nomor_surat").html("0301R0010120K000003");
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
                var data = response.response[0];
                if (data.jnsKontrol == 1) {
                    $("#title-surat").text("Surat Perintah Rawat Inap");
                } else {
                    $("#title-surat").text("Surat Rencana Kontrol");

                }
                $("#rk_dokter").html(data.namaDokter + "<br>" + data.namaPoliTujuan);
                $("#rk_nomor_kartu").html(data.sep.noKartu);
                $("#rk_nama_pasien").html(data.sep.nama + " (" + data.sep.kelamin + ")");
                $("#rk_tanggal_lahir").html(data.sep.tglLahir);
                $("#rk_diagnosa_awal").html(data.sep.diagnosa);

                var dateNow = new Date();
                var tgl_cetak = str_pad(2, dateNow.getDate()) + "/" + str_pad(2, dateNow.getMonth() + 1) + "/" + dateNow.getFullYear() + " " + dateNow.getHours() + ":" + dateNow.getMinutes() + ":" + dateNow.getSeconds();
                $("#tgl_cetak").html("Tgl. Cetak " + tgl_cetak);

                $("#rk_nomor_surat").html(data.noSuratkontrol);
                $("#rk_tanggal_terbit").html(data.tglTerbit);

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
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> SPRI / Rencana Kontrol <code>baru</code>
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
                                <!-- <div class="col-6 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_rk_pasien" class="form-control uppercase"></select>
                                </div> -->
                                <div class="col-6 form-group">
                                    <label for="">Jenis Kontrol</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_jenis">
                                        <option value="1">SPRI</option>
                                        <option value="2">Rencana Kontrol</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="" id="switch_jenis">No. Kartu</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_spri_noKartu" />
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Tanggal Rencana Kontrol</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_spri_tglRencanaKontrol" />
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Poli/Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_poliKontrol"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Kode Dokter</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_kodeDokter"></select>
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
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> SPRI / Rencana Kontrol <code>edit</code>
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
                                <!-- <div class="col-6 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_rk_pasien" class="form-control uppercase"></select>
                                </div> -->
                                <div class="col-6 form-group">
                                    <label for="">Jenis Kontrol</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_jenis">
                                        <option value="1">SPRI</option>
                                        <option value="2">Rencana Kontrol</option>
                                    </select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="" id="switch_jenis">No. Kartu</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_spri_noKartu" />
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Tanggal Rencana Kontrol</label>
                                    <input type="date" autocomplete="off" class="form-control uppercase" id="txt_bpjs_spri_tglRencanaKontrol" />
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Poli/Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_poliKontrol"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Kode Dokter</label>
                                    <select class="form-control uppercase" id="txt_bpjs_spri_kodeDokter"></select>
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
                    <div class="col-6 offset-sm-2" id="data_rk_cetak_kiri">
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
                                <td>Tgl. Entri</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_tanggal_terbit"></td>
                            </tr>
                            <tr>
                                <td colspan="3">Demikian atas bantuannya diucapkan banyak terima kasih</td>
                            </tr>
                            <tr>
                                <td colspan="3" id="tgl_cetak" style="padding-top: 50px;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_rk_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td>No.Surat</td>
                            </tr>
                            <tr>
                                <td id="rk_nomor_surat"></td>
                            </tr>
                            <tr>
                                <td id="rk_tanggal_terbit"></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 100px;">
                                    <div style="border-bottom: solid 1px #000; height: 100px; margin: 10px; width:200px;">
                                        Mengetahui
                                    </div>
                                </td>
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