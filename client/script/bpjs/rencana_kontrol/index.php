<script type="text/javascript">
    $(function() {

        $("#tglawal_rk").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#tglakhir_rk").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true
        }).datepicker("setDate", new Date());

        $("#txt_bpjs_rk_tglRencanaKontrol").datepicker({
            dateFormat: "DD, dd MM yy",
            autoclose: true
        }).datepicker("setDate", new Date());

        var selectedKartu = "";
        var selected_SPRI = "";

        var refreshData = 'N';
        var SPRINo = "";
        var MODE = "ADD";

        var DataRK = $("#table-rk").DataTable({
            // processing: true,
            // serverSide: true,
            // sPaginationType: "full_numbers",
            // bPaginate: true,
            // serverMethod: "POST",
            "ajax": {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/listrencanakontrol`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                data: function(d) {
                    var tgl_awal = new Date($("#tglakhir_rk").datepicker("getDate"));
                    var parse_tgl_awal = tgl_awal.getFullYear() + "-" + str_pad(2, tgl_awal.getMonth() + 1) + "-" + str_pad(2, tgl_awal.getDate());
                    var tgl_akhir = new Date($("#tglakhir_rk").datepicker("getDate"));
                    var parse_tgl_akhir = tgl_akhir.getFullYear() + "-" + str_pad(2, tgl_akhir.getMonth() + 1) + "-" + str_pad(2, tgl_akhir.getDate());

                    // d.request = "get_history_spri_local";
                    d.tglawal = parse_tgl_awal;
                    d.tglakhir = parse_tgl_akhir;
                    d.filter = 2;
                    // d.sync_bpjs = refreshData;
                },
                dataSrc: function(response) {
                    console.clear();
                    console.log(response)
                    var data = response.response;
                    if (data === undefined || data === null) {
                        return [];
                    } else {
                        return data;
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
                        return row.noSuratKontrol;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.tglTerbitKontrol;
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
                        return row.kodeDokter + " - " + row.namaDokter;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-warning btn-sm printRk\" no-sep=\"" + row.noSep + "\" id=\"rk_print_" + row.noSuratKontrol + "\">" +
                            "<i class=\"fa fa-print\"></i> Cetak" +
                            "</button>" +
                            "<button class=\"btn btn-info btn-sm btnEditRK\" no-sep=\"" + row.noSep + "\" id=\"rk_edit_" + row.noSuratKontrol + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusRK\" id=\"hapus_" + row.noSuratKontrol + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#btn_sync_bpjs").click(function() {
            refreshData = 'Y';
            DataRK.ajax.reload(function() {
                refreshData = 'N';
            });
        });

        $("body").on("click", ".btnHapusRK", function() {
            var NO_SRK = $(this).attr("id").split("_");
            NO_SRK = NO_SRK[NO_SRK.length - 1];
            Swal.fire({
                title: "BPJS Rencana Kontrol",
                text: "Hapus NO_SRK " + NO_SRK + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: `${__BPJS_SERVICE_URL__}rc/sync.sh/deleterc`,
                        type: "DELETE",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: {
                            "t_suratkontrol": {
                                "noSuratKontrol": NO_SRK,
                                "user": __MY_NAME__
                            }
                        },
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.metadata.code) === 200) {
                                Swal.fire(
                                    'BPJS Rencana Kontrol',
                                    'Rencana Kontrol Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    DataRK.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS Rencana Kontrol',
                                    response.metaData.message,
                                    'error'
                                ).then((result) => {
                                    DataRK.ajax.reload();
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

        $("body").on("click", ".btnEditRK", function() {
            var NO_SRK = $(this).attr("id").split("_");
            NO_SRK = NO_SRK[NO_SRK.length - 1];
            MODE = "EDIT";

            $("#modal-rk").modal("show");
            // $("#txt_bpjs_rk_noSep").focus();

            //Load Detail
            $.ajax({
                async: false,
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisuratkontrol`,
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
                        nosuratkontrol: NO_SRK
                    };
                },
                success: function(response) {

                    var data = response.response[0];

                    $("#txt_bpjs_rk_noSep").append("<option value=\"" + data.noSep + "\">" + data.noSep + "</option>");
                    $("#txt_bpjs_rk_noSep").select2("data", {
                        id: data.noSep,
                        text: data.noSep
                    });
                    $("#txt_bpjs_rk_noSep").trigger("change");
                    $("#txt_bpjs_rk_noSep").attr({
                        "disabled": "disabled"
                    });

                    $("#txt_bpjs_rk_tglRencanaKontrol").val(data.tglRencanaKontrol);

                    $("#txt_bpjs_rk_poliKontrol").append("<option value=\"" + data.poliTujuan + "\">" + data.poliTujuan + " - " + data.namaPoliTujuan + "</option>");
                    $("#txt_bpjs_rk_poliKontrol").select2("data", {
                        id: data.poliTujuan,
                        text: data.namaPoliTujuan
                    });
                    $("#txt_bpjs_rk_poliKontrol").trigger("change");

                    $("#txt_bpjs_rk_kodeDokter").append("<option value=\"" + data.kodeDokter + "\">" + data.kodeDokter + " - " + data.mamaDokter + "</option>");
                    $("#txt_bpjs_rk_kodeDokter").select2("data", {
                        id: data.kodeDokter,
                        text: data.mamaDokter
                    });
                    $("#txt_bpjs_rk_kodeDokter").trigger("change");

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        $("#txt_bpjs_rk_noSEP").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "No.SEP tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-rk'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisep`,
                // url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisep?nomorsep=0032R0110723V010991`,
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
                        nomorsep: term.term
                    };
                },
                processResults: function(response) {
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_rk_noSEP").trigger("change.select2");
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.noSep,
                                    id: item.noSep
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

        });


        $("#txt_bpjs_rk_poliKontrol").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function() {
                    return "Poli tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-rk'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/listspesialistik/?jeniskontrol=1&nomor=` + $("#txt_bpjs_rk_noSEP").val() + `&tglrencanakontrol=` + $("#txt_bpjs_rk_tglRencanaKontrol").val(),
                // url: `${__BPJS_SERVICE_URL__}rc/sync.sh/listspesialistik/?jeniskontrol=1&nomor=0000267050799&tglrencanakontrol=2023-07-31`,
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
                        $("#txt_bpjs_rk_poliKontrol").trigger("change.select2");
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
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

        });

        var tglRencanaKontrol = new Date($("#txt_bpjs_rk_tglRencanaKontrol").datepicker("getDate"));
        var parse_tglRencanaKontrol = tglRencanaKontrol.getFullYear() + "-" + str_pad(2, tglRencanaKontrol.getMonth() + 1) + "-" + str_pad(2, tglRencanaKontrol.getDate());


        $("#txt_bpjs_rk_kodeDokter").select2({
            dropdownParent: $('#modal-rk'),
            ajax: {
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=2&kodepoli=` + $("#txt_bpjs_rk_poliKontrol").val() + `&tglrencanakontrol=` + parse_tglRencanaKontrol,
                // url: `${__BPJS_SERVICE_URL__}rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=2&kodepoli=ANA&tglrencanakontrol=2023-07-31`,
                type: "GET",
                dataType: "json",
                crossDomain: true,
                beforeSend: async function(request) {
                    request.setRequestHeader("Accept", "application/json");
                    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                    request.setRequestHeader("x-token", bpjs_token);
                },
                processResults: function(response) {
                    if (response.metadata.code !== 200) {
                        $("#txt_bpjs_rk_kodeDokter").trigger("change.select2");
                    } else {
                        var data = response.response;
                        console.log(data);
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
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

        });

        $("body").on("click", "#btnTambahRK", function() {
            $("#modal-rk").modal("show");
            $("#txt_bpjs_rk_noSep").focus();
            MODE = "ADD";
        });

        $("#txt_bpjs_rk_spesialistik_dpjp").select2({
            dropdownParent: $('#modal-rk')
        });

        $("#txt_bpjs_rk_dpjp").select2({
            dropdownParent: $('#modal-rk')
        });


        $("#btnProsesRK").click(function() {
            // var pasien = $("#txt_bpjs_rk_pasien option:selected").val();
            // var sep = $("#txt_bpjs_rk_sep option:selected").val();
            // var kartu = selectedKartu;
            // var alamat = $("#txt_bpjs_rk_alamat").val();
            // var email = $("#txt_bpjs_rk_email").val();
            // var kodeDPJP = $("#txt_bpjs_rk_dpjp option:selected").val();
            // var jenis_layan = $("#txt_bpjs_rk_jenis_layan_dpjp option:selected").val();
            // var spesialistik = $("#txt_bpjs_rk_spesialistik_dpjp option:selected").val();
            // var spesialistik_text = $("#txt_bpjs_rk_spesialistik_dpjp option:selected").text();
            // var jenkel = $("#txt_bpjs_rk_jenkel").val();
            // var kontak = $("#txt_bpjs_rk_kontak").val();
            // var poli_tujuan = $("#txt_bpjs_rk_poli").val();
            // var poli_text = $("#txt_bpjs_rk_poli option:selected").text();
            // var poli_asal = $("#txt_bpjs_rk_sep option:selected").attr("poli-asal");
            // var tanggal = $("#txt_bpjs_rk_tanggal").val();

            var no_SEP = $("#txt_bpjs_rk_noSEP").val();

            var tglRencanaKontrol = new Date($("#txt_bpjs_rk_tglRencanaKontrol").datepicker("getDate"));
            var parse_tglRencanaKontrol = tglRencanaKontrol.getFullYear() + "-" + str_pad(2, tglRencanaKontrol.getMonth() + 1) + "-" + str_pad(2, tglRencanaKontrol.getDate());
            var tanggal_kontrol = parse_tglRencanaKontrol;

            var poli_kontrol = $("#txt_bpjs_rk_poliKontrol").val();
            var kode_dokter = $("#txt_bpjs_rk_kodeDokter").val();

            Swal.fire({
                title: "BPJS Rencana Kontrol",
                text: "Buat Rencana Kontrol baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `${__BPJS_SERVICE_URL__}rc/sync.sh/insertrcspri`,
                        type: "POST",
                        dataType: "json",
                        crossDomain: true,
                        beforeSend: async function(request) {
                            request.setRequestHeader("Accept", "application/json");
                            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            request.setRequestHeader("x-token", bpjs_token);
                        },
                        data: {
                            "noSEP": no_SEP,
                            "noKartu": no_kartu,
                            "kodeDokter": kode_dokter,
                            "poliKontrol": poli_kontrol,
                            "tglRencanaKontrol": tanggal_kontrol, //format:"2021-04-13"
                            "user": __MY_NAME__
                        },
                        // data: {
                        //     request: (MODE === "ADD") ? "spri_baru" : "spri_edit",
                        //     no_spri: selected_SPRI,
                        //     sep: sep,
                        //     kartu: kartu,
                        //     alamat: alamat,
                        //     pasien: pasien,
                        //     email: email,
                        //     dpjp: kodeDPJP,
                        //     jenkel: jenkel,
                        //     jenis_layan: jenis_layan,
                        //     spesialistik: spesialistik,
                        //     spesialistik_text: spesialistik_text,
                        //     telp: kontak,
                        //     poli_tujuan: poli_tujuan,
                        //     poli_asal: poli_asal,
                        //     poli_text: poli_text,
                        //     tanggal: tanggal
                        // },
                        success: function(response) {
                            if (parseInt(response.metaData.code) === 200) {
                                Swal.fire(
                                    "Rencana Kontrol",
                                    "Rencana Kontrol berhasil diproses",
                                    "success"
                                ).then((result) => {
                                    $("#modal-rk").modal("hide");
                                    DataRK.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Gagal buat Rencana Kontrol",
                                    response.metaData.message,
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
        });
    });

    $("body").on("click", "#btnCetakRkTest", function() {
        $("#modal-cetak-rk").modal("show");
    });

    $("body").on("click", ".printRk", function() {
        var NO_SRK = $(this).attr("id").split("_");
        NO_SRK = NO_SRK[NO_SRK.length - 1];

        $.ajax({
            async: false,
            url: `${__BPJS_SERVICE_URL__}rc/sync.sh/carisuratkontrol`,
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
                    nosuratkontrol: NO_SRK
                };
            },
            success: function(response) {
                var dataRK = response.response[0];
                $("#rk_dokter").html(dataRK.mamaDokter + "<br>" + dataRK.mamaPoliTujuan);
                $("#rk_nomor_kartu").html(dataRK.sep.noKartu);
                $("#rk_nama_pasien").html(dataRK.sep.nama);
                $("#rk_tanggal_lahir").html(dataRK.sep.tglLahir);
                $("#rk_diagnosa_awal").html(dataRK.sep.diagnosa);
                $("#rk_tanggal_terbit").html(dataRK.tglTerbit);

                var dateNow = new Date();
                var tgl_cetak = dateNow.getDate() + "/" + dateNow.getMonth() + "/" + dateNow.getFullYear() + " " + dateNow.getHours() + " : " + dateNow.getMinutes() + " : " + dateNow.getSeconds();
                $("#tgl_cetak").html(tgl_cetak);

                $("#rk_nomor_surat").html(dataRK.noSuratkontrol);
                $("#rk_tanggal_terbit").html(dataRK.tglTerbit);

                $("#modal-cetak-rk").modal("show");
            },
            error: function(response) {
                //
            }
        });
    });

    $("#btnCetakRK").click(function() {
        $.ajax({
            async: false,
            url: __HOST__ + "miscellaneous/print_template/bpjs_rk.php",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "POST",
            data: {
                __PC_CUSTOMER__: __PC_CUSTOMER__,
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




<div id="modal-rk" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rencana Kontrol Baru <code>Baru</code>
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
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rencana Kontrol</h5>
                            </div>
                            <div class="card-body row">
                                <!-- <div class="col-6 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_rk_pasien" class="form-control uppercase"></select>
                                </div> -->
                                <div class="col-6 form-group">
                                    <label for="">No. SEP</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_noSEP"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Tanggal Rencana Kontrol</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rk_tglRencanaKontrol">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Poli/Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_poliKontrol"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Kode Dokter</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_kodeDokter"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesRK">
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
                <div class="row">
                    <div class="col-md-6">
                        <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" />
                    </div>
                    <div>
                        <h5 class="modal-title" id="modal-large-title">
                            <span>Surat Rencana Kontrol</span><br>
                            <span>RSUD PETALA BUMI</span>
                        </h5>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6" id="data_rk_cetak_kiri">
                        <table class="table form-mode">
                            <tr>
                                <td>Kepada Yth.</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_dokter">DR.dr.H Eva Decroli, SpPD K-EMD Finasim<br>ENDOKRIN-METABOLIK-DIABETES</td>
                            </tr>
                            <tr>
                                <td colspan="3">Mohon Pemeriksaan dan Penanganan Lebih Lanjut:</td>
                            </tr>
                            <tr>
                                <td>No. Kartu</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_nomor_kartu">OOOBO154504O1</td>
                            </tr>
                            <tr>
                                <td>Nama Pasienn</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_nama_pasien">PIASDIL (Laki-laki)</td>
                            </tr>
                            <tr>
                                <td>Tgl. Lahir</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_tanggal_lahir">14 Agustus 1999</td>
                            </tr>
                            <tr>
                                <td>Diagnosa Awal</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_diagnosa_awal">EI1 - Non-insulin-dependent diabetes mellitus</td>
                            </tr>
                            <tr>
                                <td>Tgl. Entri</td>
                                <td class="wrap_content">:</td>
                                <td id="rk_tanggal_terbit">14 Agustus 2023</td>
                            </tr>
                            <tr>
                                <td colspan="3">Demikian atas bantuannya diucapkan banyak terima kasih</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="pt-5" id="tgl_cetak"><small>Tgl. Cetak 14/08/2023 12.00 PM</small></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-4" id="data_rk_cetak_kanan">
                        <table class="table form-mode">
                            <tr>
                                <td>No.Surat</td>
                            </tr>
                            <tr>
                                <td id="rk_nomor_surat">0301R0010120K000003</td>
                            </tr>
                            <tr>
                                <td id="rk_tanggal_terbit">Tgl. 14 Agustus 2023</td>
                            </tr>
                            <tr>
                                <td class="pt-5">Mengetahuai</td>
                            </tr>
                            <tr>
                                <td class="pt-5">_______________</td>
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