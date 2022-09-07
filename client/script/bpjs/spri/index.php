<script type="text/javascript">
    $(function() {
        var selectedKartu = "";
        var selected_SPRI = "";

        var refreshData = 'N';
        var SPRINo = "";
        var MODE = "ADD";





        var DataSPRI = $("#table-spri").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function (d) {
                    d.request = "get_history_spri_local";
                    d.dari = getDateRange("#range_spri")[0];
                    d.sampai = getDateRange("#range_spri")[1];
                    d.pelayanan_jenis = 1;
                    d.sync_bpjs = refreshData;
                },
                dataSrc: function (response) {
                    console.clear();
                    console.log(response)
                    var data = response.response_package.response_data;
                    if (data === undefined || data === null) {
                        return [];
                    } else {
                        return data;
                    }

                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parsed + "</span>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return (parseInt(row.jenis_layan) === 1) ? "Rawat Inap<br /><code>SPRI</code>" : "Rawat Jalan";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.no_spri;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.pasien.no_rm + " - " + row.pasien.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.poli_tujuan_text + "<br /><b class=\"text-info\">" + row.dpjp_nama + "</b></span>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
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
            DataSPRI.ajax.reload(function () {
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
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "DELETE",
                        success: function (response) {
                            console.clear();
                            console.log(response);
                            if(parseInt(response.response_package.bpjs.value.metaData.code) === 200) {
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
                        error: function (response) {
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

            

            $("#modal-prb").modal("show");
            $("#txt_bpjs_rk_nomor_kartu").focus();

            //Load Detail
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_detail_spri/" + SPRIUID,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    
                    var data = response.response_package.response_data[0];

                    $("#txt_bpjs_rk_pasien").append("<option value=\"" + data.pasien.uid + "\">" + data.pasien.no_rm + " - " + data.pasien.nama + "</option>");
                    $("#txt_bpjs_rk_pasien").select2("data", {id: data.pasien.uid, text: data.pasien.no_rm + " - " + data.pasien.nama});
                    $("#txt_bpjs_rk_pasien").trigger("change");
                    
                    $("#txt_bpjs_rk_pasien").attr({
                        "disabled": "disabled"
                    });

                    selected_SPRI = data.no_spri;

                    var restMeta = data.pasien.history_penjamin;
                    for(var b in restMeta) {
                        if(restMeta[b].penjamin === __UIDPENJAMINBPJS__) {
                            var bpjsData = JSON.parse(restMeta[b].rest_meta);
                            selectedKartu = bpjsData.data.peserta.noKartu;
                        }
                    }

                    //$("#txt_bpjs_rk_jenis_layan_dpjp").append("<option value=\"" + data.jenis_layan + "\">" + ((data.jenis_layan == 1) ? "Rawat Inap" : "Rawat Jalan") + "</option>");
                    $("#txt_bpjs_rk_jenis_layan_dpjp").select2("data", {id: data.jenis_layan, text: ((data.jenis_layan == 1) ? "Rawat Inap" : "Rawat Jalan")});
                    $("#txt_bpjs_rk_jenis_layan_dpjp").trigger("change");

                    loadSpesialistik("#txt_bpjs_rk_spesialistik_dpjp", data.spesialistik);

                    $("#txt_bpjs_rk_poli").append("<option value=\"" + data.poli_tujuan + "\">" + data.poli_tujuan_text + "</option>");
                    $("#txt_bpjs_rk_poli").select2("data", {id: data.poli_tujuan, text: data.poli_tujuan_text});
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


        

        $("#txt_bpjs_rk_pasien").select2({
            minimumInputLength: 2,
            language: {
                noResults: function() {
                    return "Pasien tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-prb'),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Pasien/select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.response_data;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.text,
                                id: item.uid,
                                email: item.email,
                                no_telp: item.no_telp,
                                jenkel_detail: item.jenkel_detail,
                                history_penjamin: item.history_penjamin
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
            
            var restMeta = data.history_penjamin;
            for(var b in restMeta) {
                if(restMeta[b].penjamin === __UIDPENJAMINBPJS__) {
                    var bpjsData = JSON.parse(restMeta[b].rest_meta);
                    selectedKartu = bpjsData.data.peserta.noKartu;
                }
            }

            $("#txt_bpjs_rk_email").val((data.email !== undefined && data.email !== null) ? data.email : "-");
            $("#txt_bpjs_rk_kontak").val((data.no_telp !== undefined && data.no_telp !== null) ? data.no_telp : "-");
            $("#txt_bpjs_rk_jenkel").val((data.jenkel_detail.nama !== undefined && data.jenkel_detail.nama !== null) ? data.jenkel_detail.nama : "-");
            $("#txt_bpjs_rk_alamat").val((data.alamat !== undefined && data.alamat !== null) ? data.alamat : "-");

            load_sep(data.id);
        });

        $("#txt_bpjs_rk_jenis_layan_dpjp").select2({
            dropdownParent: $('#modal-prb')
        }).on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_rk_dpjp");
        });

        loadSpesialistik("#txt_bpjs_rk_spesialistik_dpjp");
        function loadSpesialistik(target, selected = "") {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_spesialistik",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.data.list;

                    $(target + " option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        if(data[a].kode === selected) {
                            $(selection).attr({
                                "selected": "selected"
                            });
                        }
                        $(target).append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        function loadDPJPSpesialis(target, selected = "") {
            $.ajax({
                url:__HOSTAPI__ + "/BPJS/get_dpjp/?jenis=" + $("#txt_bpjs_rk_jenis_layan_dpjp option:selected").val() + "&spesialistik=" + $("#txt_bpjs_rk_spesialistik_dpjp option:selected").val() + "&tanggal=" + $("#txt_bpjs_rk_sep option:selected").attr("tanggal-sep"),
                type: "GET",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    if(parseInt(response.response_package.metaData.code) === 200) {
                        var data = response.response_package.data.list;
                        $(target + " option").remove();
                        for(var a = 0; a < data.length; a++) {
                            var selection = document.createElement("OPTION");

                            if(data[a].kode === selected) {
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

        $("#txt_bpjs_rk_sep").select2({
            dropdownParent: $('#modal-prb')
        }).on("select2:select", function(e) {
            loadDPJPSpesialis("#txt_bpjs_rk_dpjp");
        });

        

        $("#txt_bpjs_rk_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $('#modal-prb'),
            ajax: {
                quietMillis: 1000,
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_poli",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.data.poli;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });


        function load_sep(pasien, selected = "") {
            $.ajax({
                url:__HOSTAPI__ + "/BPJS/get_sep_pasien/" + pasien,
                type: "GET",
                async: false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response) {
                    var data = response.response_package.response_data;
                    $("#txt_bpjs_rk_sep option").remove();
                    for(var a in data) {
                        $("#txt_bpjs_rk_sep").append("<option " + ((data[a].sep_no == selected) ? "selected=\"selected\"" : "") + " poli-asal=\"" + data[a].poli_tujuan + "\" tanggal-sep=\"" + data[a].tanggal_sep + "\" value=\"" + data[a].sep_no + "\">" + data[a].sep_no + "</option>");
                    }

                    loadDPJPSpesialis("#txt_bpjs_rk_dpjp");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#btnTambahPRB").click(function() {
            $("#modal-prb").modal("show");
            $("#txt_bpjs_rk_pasien").removeAttr("disabled");
            $("#txt_bpjs_rk_sep").removeAttr("disabled");
            $("#txt_bpjs_rk_nomor_kartu").focus();
            MODE = "ADD";
        });

        $("#txt_bpjs_rk_spesialistik_dpjp").select2({
            dropdownParent: $('#modal-prb')
        });

        $("#txt_bpjs_rk_dpjp").select2({
            dropdownParent: $('#modal-prb')
        });

        
        $("#btnProsesPRB").click(function() {
            var pasien = $("#txt_bpjs_rk_pasien option:selected").val();
            var sep = $("#txt_bpjs_rk_sep option:selected").val();
            var kartu = selectedKartu;
            var alamat = $("#txt_bpjs_rk_alamat").val();
            var email = $("#txt_bpjs_rk_email").val();
            var kodeDPJP = $("#txt_bpjs_rk_dpjp option:selected").val();
            var jenis_layan = $("#txt_bpjs_rk_jenis_layan_dpjp option:selected").val();
            var spesialistik = $("#txt_bpjs_rk_spesialistik_dpjp option:selected").val();
            var spesialistik_text = $("#txt_bpjs_rk_spesialistik_dpjp option:selected").text();
            var jenkel = $("#txt_bpjs_rk_jenkel").val();
            var kontak = $("#txt_bpjs_rk_kontak").val();
            var poli_tujuan = $("#txt_bpjs_rk_poli").val();
            var poli_text = $("#txt_bpjs_rk_poli option:selected").text();
            var poli_asal = $("#txt_bpjs_rk_sep option:selected").attr("poli-asal");
            var tanggal = $("#txt_bpjs_rk_tanggal").val();
            

            Swal.fire({
                title: "BPJS Rencana Kontrol",
                text: "Buat Rencana Kontrol baru?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/BPJS",
                        type: "POST",
                        data: {
                            request: (MODE === "ADD") ? "spri_baru" : "spri_edit",
                            no_spri: selected_SPRI,
                            sep: sep,
                            kartu: kartu,
                            alamat: alamat,
                            pasien: pasien,
                            email: email,
                            dpjp: kodeDPJP,
                            jenkel: jenkel,
                            jenis_layan: jenis_layan,
                            spesialistik: spesialistik,
                            spesialistik_text: spesialistik_text,
                            telp: kontak,
                            poli_tujuan: poli_tujuan,
                            poli_asal: poli_asal,
                            poli_text: poli_text,
                            tanggal: tanggal
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response) {
                            if(parseInt(response.response_package.bpjs.metaData.code) === 200) {
                                Swal.fire(
                                    "Rencana Kontrol",
                                    "Rencana Kontrol berhasil diproses",
                                    "success"
                                ).then((result) => {
                                    $("#modal-prb").modal("hide");
                                    DataSPRI.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Gagal buat Rencana Kontrol",
                                    response.response_package.bpjs.metaData.message,
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
</script>




<div id="modal-prb" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Rencana Kontrol Baru <code>SPRI</code>
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
                                <div class="col-8 form-group">
                                    <label for="">Pasien</label>
                                    <select id="txt_bpjs_rk_pasien" class="form-control uppercase"></select>
                                </div>
                                <div class="col-4 form-group">
                                    <label for="">SEP</label>
                                    <select id="txt_bpjs_rk_sep" class="form-control uppercase"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Email</label>
                                    <input type="email" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rk_email" readonly />
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Kontak</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rk_kontak" readonly />
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rk_jenkel" readonly />
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Alamat</label>
                                    <textarea class="form-control" id="txt_bpjs_rk_alamat" readonly></textarea>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_jenis_layan_dpjp">
                                        <option value="1">Rawat Inap</option>
                                        <option value="2">Rawat Jalan</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">Spesialistik</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_spesialistik_dpjp"></select>
                                </div>
                                <div class="col-3 form-group">
                                    <label for="">DPJP</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_dpjp"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Poli Rencana Kontrol</label>
                                    <select class="form-control uppercase" id="txt_bpjs_rk_poli"></select>
                                </div>
                                <div class="col-6 form-group">
                                    <label for="">Tanggal Rencana Kontrol (Rajal) / Tanggal SPRI (Ranap)</label>
                                    <input type="date" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rk_tanggal" />
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