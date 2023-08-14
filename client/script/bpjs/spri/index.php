<script type="text/javascript">
    $(async function() {
        var selectedKartu = "";
        var selected_SPRI = "";

        var refreshData = 'N';
        var SPRINo = "";
        var MODE = "ADD";
        var getUrl = `${__BPJS_SERVICE_URL__}rc/sync.sh/carisuratkontrol/?nosuratkontrol=0301R0010120K000003`;
        var getParameter = {
            nosuratkontrol: '0301R0010120K000003'
        }

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
                    if(parseInt(response.metadata.code) !== 200) {
                        return [];
                    } else {
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
                        return "<span class=\"wrap_content\">" + row.created_at_parsed + "</span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return (parseInt(row.jenis_layan) === 1) ? "Rawat Inap<br /><code>SPRI</code>" : "Rawat Jalan";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.no_spri;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return row.pasien.no_rm + " - " + row.pasien.nama;
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.poli_tujuan_text + "<br /><b class=\"text-info\">" + row.dpjp_nama + "</b></span>";
                    }
                },
                {
                    "data": null,
                    render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btn-sm btnEditSPRI\" no-spri=\"" + row.no_spri + "\" id=\"sep_edit_" + row.uid + "\">" +
                            "<i class=\"fa fa-pencil-alt\"></i> Edit" +
                            "</button>" +
                            "<button class=\"btn btn-danger btnHapusSPRI\" id=\"hapus_" + row.no_spri + "\"><i class=\"fa fa-ban\"></i> Hapus</button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#btn_sync_bpjs").click(function() {
            refreshData = 'Y';
            DataSPRI.ajax.reload(function() {
                refreshData = 'N';
            });
        });

        $("body").on("click", ".btnHapusSPRI", function() {
            var SPRI = $(this).attr("id").split("_");
            SPRI = SPRI[SPRI.length - 1];
            Swal.fire({
                title: "BPJS SPRI",
                text: "Hapus SPRI " + SPRI + "?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/BPJS/SPRI/" + SPRI,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "DELETE",
                        success: function(response) {
                            console.clear();
                            console.log(response);
                            if (parseInt(response.response_package.bpjs.value.metaData.code) === 200) {
                                Swal.fire(
                                    'BPJS SPRI',
                                    'SPRI Berhasil dihapus',
                                    'success'
                                ).then((result) => {
                                    DataSPRI.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    'BPJS SPRI',
                                    response.response_package.bpjs.value.metaData.message,
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
            var SPRIUID = $(this).attr("id").split("_");
            SPRIUID = SPRIUID[SPRIUID.length - 1];
            MODE = "EDIT";

            $("#modal-spri").modal("show");
            $("#txt_bpjs_rk_nomor_kartu").focus();

            //Load Detail
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/BPJS/get_detail_spri/" + SPRIUID,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {

                    var data = response.response_package.response_data[0];

                    $("#txt_bpjs_rk_pasien").append("<option value=\"" + data.pasien.uid + "\">" + data.pasien.no_rm + " - " + data.pasien.nama + "</option>");
                    $("#txt_bpjs_rk_pasien").select2("data", {
                        id: data.pasien.uid,
                        text: data.pasien.no_rm + " - " + data.pasien.nama
                    });
                    $("#txt_bpjs_rk_pasien").trigger("change");

                    $("#txt_bpjs_rk_pasien").attr({
                        "disabled": "disabled"
                    });

                    selected_SPRI = data.no_spri;

                    var restMeta = data.pasien.history_penjamin;
                    for (var b in restMeta) {
                        if (restMeta[b].penjamin === __UIDPENJAMINBPJS__) {
                            var bpjsData = JSON.parse(restMeta[b].rest_meta);
                            selectedKartu = bpjsData.data.peserta.noKartu;
                        }
                    }

                    //$("#txt_bpjs_rk_jenis_layan_dpjp").append("<option value=\"" + data.jenis_layan + "\">" + ((data.jenis_layan == 1) ? "Rawat Inap" : "Rawat Jalan") + "</option>");
                    $("#txt_bpjs_rk_jenis_layan_dpjp").select2("data", {
                        id: data.jenis_layan,
                        text: ((data.jenis_layan == 1) ? "Rawat Inap" : "Rawat Jalan")
                    });
                    $("#txt_bpjs_rk_jenis_layan_dpjp").trigger("change");

                    loadSpesialistik("#txt_bpjs_rk_spesialistik_dpjp", data.spesialistik);

                    $("#txt_bpjs_rk_poli").append("<option value=\"" + data.poli_tujuan + "\">" + data.poli_tujuan_text + "</option>");
                    $("#txt_bpjs_rk_poli").select2("data", {
                        id: data.poli_tujuan,
                        text: data.poli_tujuan_text
                    });
                    $("#txt_bpjs_rk_poli").trigger("change");

                    $("#txt_bpjs_rk_tanggal").val(data.tgl_rencana_kontrol);


                    $("#txt_bpjs_rk_email").val((data.pasien.email !== undefined && data.pasien.email !== null) ? data.pasien.email : "-");
                    $("#txt_bpjs_rk_kontak").val((data.pasien.no_telp !== undefined && data.pasien.no_telp !== null) ? data.pasien.no_telp : "-");
                    $("#txt_bpjs_rk_jenkel").val((data.pasien.jenkel_detail.nama !== undefined && data.pasien.jenkel_detail.nama !== null) ? data.pasien.jenkel_detail.nama : "-");
                    $("#txt_bpjs_rk_alamat").val((data.pasien.alamat !== undefined && data.pasien.alamat !== null) ? data.pasien.alamat : "-");

                    load_sep(data.pasien.uid, data.no_sep);
                    loadDPJPSpesialis("#txt_bpjs_rk_dpjp", data.dpjp_kode);
                    $("#txt_bpjs_rk_sep").attr({
                        "disabled": "disabled"
                    });

                },
                error: function(response) {
                    console.log(response);
                }
            });

        });

        function refreshSpesialistik() {
            $.ajax({
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/listspesialistik/?jeniskontrol=${$("#txt_bpjs_spri_jenis").val()}&nomor=${$("#txt_bpjs_spri_noKartu").val()}&tglrencanakontrol=${$("#txt_bpjs_spri_tglRencanaKontrol").val()}`,
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
                        for(const a in data) {
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
                url: `${__BPJS_SERVICE_URL__}rc/sync.sh/jadwalpraktekdokter/?jeniskontrol=${$("#txt_bpjs_spri_jenis").val()}&kodepoli=${$("#txt_bpjs_spri_poliKontrol").val()}&tglrencanakontrol=${$("#txt_bpjs_spri_tglRencanaKontrol").val()}`,
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
                        for(const a in data) {
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

        $("#txt_bpjs_spri_noKartu").on("keyup", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_poliKontrol").on("select2:select", function(e) {
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_jenis").on("keyup", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_tglRencanaKontrol").on("keyup", function() {
            refreshSpesialistik();
            refreshJadwalDokter();
        });

        $("#txt_bpjs_spri_jenis").select2().on("select2:select", function(e) {
            refreshSpesialistik();
            refreshJadwalDokter();
            if($("#txt_bpjs_spri_jenis").val() == 1) {
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

            var no_kartu = $("#txt_bpjs_spri_noKartu").val();
            var tanggal_kontrol = $("#txt_bpjs_spri_tglRencanaKontrol").val();
            var poli_kontrol = $("#txt_bpjs_spri_poliKontrol").val();
            var kode_dokter = $("#txt_bpjs_spri_kodeDokter").val();

            Swal.fire({
                title: "BPJS Rencana Kontrol",
                text: "Buat Rencana Kontrol baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    if($("#txt_bpjs_spri_jenis").val() == 1) {
                        $.ajax({
                            url: `${__BPJS_SERVICE_URL__}rc/sync.sh/insertrcspri`,
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
                            url: `${__BPJS_SERVICE_URL__}rc/sync.sh/insertrc`,
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
    });
</script>




<div id="modal-spri" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> SPRI <code>baru</code>
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
                                <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi SPRI</h5>
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
                <button class="btn btn-success" id="btnProsesSPRI">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>