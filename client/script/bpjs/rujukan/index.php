<script type="text/javascript">
    $(function () {
        var currentRujukan = "", currentRujukanText = "";
        var selectedBPJS = "", selectedPasien = "";
        var RujukanList = $("#table-rujukan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Rujukan",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_all";
                },
                dataSrc:function(response) {
                    var data = response.response_package.response_data;
                    if(data === undefined) {
                        data = [];
                    }
                    
                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    
                    return data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var selectedID = {};
                        var selectedMetaData;
                        console.log(row.pasien.history_penjamin);
                        for(var a = 0; a < row.pasien.history_penjamin.length; a++) {
                            if(row.pasien.history_penjamin[a].penjamin === row.penjamin.uid) {
                                selectedID = row.pasien.history_penjamin[a];
                                selectedMetaData = JSON.parse(row.pasien.history_penjamin[a].rest_meta);
                            }
                        }

                        return "";
                        //return "<span pasien=\"" + row.pasien.uid + "\" id=\"pasien_" + row.uid + "\" nik=\"" + row.pasien.nik + "\" no_kartu=\"" + selectedMetaData.response.peserta.noKartu + "\">" + row.pasien.no_rm + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"nama_" + row.uid + "\" kontak=\"" + row.pasien.no_telp + "\">" + row.pasien.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.bpjs_rujukan !== null) {
                            var rujukanData = row.bpjs_rujukan.response_data;
                            if(rujukanData !== undefined && rujukanData !== null) {
                                return (rujukanData.length > 0) ? "<b kunjungan=\"" + ((rujukanData[0].no_rujukan === null || rujukanData[0].no_rujukan === undefined) ? "1": "0") + "\" id=\"no_kunjungan_" + rujukanData[0].uid + "\">" + ((rujukanData[0].no_rujukan === null || rujukanData[0].no_rujukan === undefined) ? rujukanData[0].no_kunjungan : rujukanData[0].no_rujukan) + "</b>" : "<b class=\"text-warning\"><i class=\"fa fa-exclamation-triangle\"></i> Belum dibuat</b>";
                            } else {
                                return "<b class=\"text-warning\"><i class=\"fa fa-exclamation-triangle\"></i> Belum dibuat</b>";
                            }
                        } else {
                            return "<b class=\"text-warning\"><i class=\"fa fa-exclamation-triangle\"></i> Belum dibuat</b>";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var now = Date.parse(<?php echo json_encode(date('Y-m-d')); ?>);
                        var currentCheck = Date.parse(row.created_at_compare);
                        var rujukanData = row.bpjs_rujukan.response_data;
                        if(rujukanData !== null && rujukanData !== undefined) {
                            if(rujukanData.length > 0) {
                                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                    "<button id=\"bpjs_edit_rujukan_" + row.bpjs_rujukan.response_data[0].uid + "\" class=\"btn btn-info btn-sm bpjs_edit_rujukan\">" +
                                    "<i class=\"fa fa-pencil-alt\"></i> Edit <span class=\"badge badge-info\">BPJS</span>" +
                                    "</button>" +
                                    "<button id=\"bpjs_hapus_rujukan_" + row.bpjs_rujukan.response_data[0].uid + "\" class=\"btn btn-danger btn-sm bpjs_hapus_rujukan\">" +
                                    "<i class=\"fa fa-trash-alt\"></i> Hapus <span class=\"badge badge-danger\">BPJS</span>" +
                                    "</button>" +
                                    "</div>";
                            } else {
                                if(currentCheck >= now) {
                                    return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        "<button id=\"rujukan_" + row.uid + "\" class=\"btn btn-success btn-sm btnRujuk\" pasien=\"" + row.pasien.uid + "\" target=\"" + row.penjamin.uid + "\"><i class=\"fa fa-check\"></i> Proses <span class=\"badge badge-success\">BPJS</span></button>" +
                                        "</div>";
                                } else {
                                    return "<b class=\"text-danger\"><i class=\"fa fa-exclamation-triangle\"></i> Habis masa berlaku</b> <button class=\"btn btn-danger btn-sm pull-right hapus_rujukan_local\" id=\"hapus_rujukan_local_" + row.uid + "\"><i class=\"fa fa-trash-alt\"></i> Hapus</button>";
                                }
                            }
                        } else {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button id=\"rujukan_" + row.uid + "\" class=\"btn btn-success btn-sm btnRujuk\" pasien=\"" + row.pasien.uid + "\" target=\"" + row.penjamin.uid + "\"><i class=\"fa fa-check\"></i> Proses <span class=\"badge badge-success\">BPJS</span></button>" +
                                "</div>";
                        }
                    }
                }
            ]
        });

        $("body").on("click", ".bpjs_hapus_rujukan", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            var isKunjungan = parseInt($("#no_kunjungan_" + id).attr("kunjungan"));

            Swal.fire({
                title: "Hapus Permintaan Rujukan?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Rujukan/bpjs_rujukan/" + $("#no_kunjungan_" + id).html().trim() + "/" + isKunjungan,
                        type: "DELETE",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            /*console.clear();
                            console.log(response);*/
                            if(isKunjungan > 0) {
                                //console.log(response);
                                //
                                if(parseInt(response.response_package.worker.response_result) > 0) {
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
                                        "Gagal hapus rujukan",
                                        "error"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            } else {
                                if(parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
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
                                        response.response_package.bpjs.content.metaData.message,
                                        "error"
                                    ).then((result) => {
                                        //
                                    });
                                }
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".bpjs_edit_rujukan", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            //TODO : Lanjut Rujukan

            currentRujukanText = $("#no_kunjungan_" + id).html().trim();
            currentRujukan = id;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Rujukan/detail/" + id,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data[0];
                    console.clear();
                    console.log(data);

                    //$("#txt_bpjs_jenis_layanan option")

                    $("#txt_bpjs_edit_nomor").val(data.peserta_no_kartu);
                    $("#txt_bpjs_edit_nik").val(data.peserta_nik);
                    $("#txt_bpjs_edit_nama").val(data.peserta_nama);
                    $("#txt_bpjs_edit_telepon").val(data.peserta_mr_no_telp);
                    $("#txt_bpjs_edit_rm").val(data.peserta_mr_no);

                    /*$("#txt_bpjs_edit_jenis_tujuan_rujukan").append("<option title=\"" + data.jenis_faskes + "\" value=\"" + data.jenis_faskes + "\">" + data.jenis_faskes_nama + "</option>");*/
                    //$("#txt_bpjs_edit_jenis_tujuan_rujukan").select2("data", {id: data.jenis_faskes, text: data.jenis_faskes_nama});
                    $("#txt_bpjs_edit_jenis_tujuan_rujukan option[value=\"" + data.pelayanan_kode + "\"]").prop("selected", true);
                    $("#txt_bpjs_edit_jenis_tujuan_rujukan").trigger("change");

                    $("#txt_bpjs_edit_tipe_rujukan option[value=\"" + data.tipe_rujukan + "\"]").prop("selected", true);
                    $("#txt_bpjs_edit_tipe_rujukan").trigger("change");

                    $("#txt_bpjs_edit_tujuan_rujukan").append("<option title=\"" + data.asal_rujukan_nama + "\" value=\"" + data.asal_rujukan_kode + "\">" + data.asal_rujukan_kode + " - " + data.asal_rujukan_nama + "</option>");
                    $("#txt_bpjs_edit_tujuan_rujukan").select2("data", {id: data.asal_rujukan_kode, text: data.asal_rujukan_nama});
                    $("#txt_bpjs_edit_tujuan_rujukan").trigger("change");

                    $("#txt_bpjs_edit_tujuan_poli").append("<option title=\"" + data.poli_tujuan_kode + "\" value=\"" + data.poli_tujuan_kode + "\">" + data.poli_tujuan_kode + " - " + data.poli_tujuan_nama + "</option>");
                    $("#txt_bpjs_edit_tujuan_poli").select2("data", {id: data.poli_tujuan_kode, text: data.poli_tujuan_nama});
                    $("#txt_bpjs_edit_tujuan_poli").trigger("change");

                    $("#txt_bpjs_edit_diagnosa").append("<option title=\"" + data.diagnosa_kode + "\" value=\"" + data.diagnosa_kode + "\">" + data.diagnosa_kode + " - " + data.diagnosa_nama + "</option>");
                    $("#txt_bpjs_edit_diagnosa").select2("data", {id: data.diagnosa_kode, text: data.diagnosa_nama});
                    $("#txt_bpjs_edit_diagnosa").trigger("change");

                    $("#txt_bpjs_edit_catatan").val(data.catatan);

                    $("#modal-rujuk-bpjs-edit").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });

        });



        $("body").on("click", ".hapus_rujukan_local", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            Swal.fire({
                title: "Hapus Permintaan Rujukan?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Rujukan/rujukan/" + id,
                        type: "DELETE",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Permohonan Rujukan",
                                    "Berhasil dihapus",
                                    "success"
                                ).then((result) => {
                                    RujukanList.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Permohonan Rujukan",
                                    "Gagal dihapus",
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

        $("#txt_bpjs_jenis_tujuan_rujukan").select2();

        $("#txt_bpjs_jenis_layanan").select2();

        $("#txt_bpjs_tujuan_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
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
                    var data = response.response_package.content.response.poli;
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

        $("#txt_bpjs_tipe_rujukan").select2();

        $("#txt_bpjs_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_diagnosa",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.diagnosa;
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

        $("#txt_bpjs_tujuan_rujukan").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_faskes_select2",
                type: "GET",
                data: function (term) {
                    return {
                        jenis:$("#txt_bpjs_jenis_tujuan_rujukan").val(),
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content;
                    if(data.metaData.message === "Sukses") {
                        return {
                            results: $.map(data.response.faskes, function (item) {
                                return {
                                    text: item.kode + " - " + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    } else {
                        /*Swal.fire(
                            "Faskes tidak ditemukan",
                            data.metaData.message,
                            "warning"
                        ).then((result) => {
                            //
                        });*/
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });





        //Edit Mode






        $("#txt_bpjs_edit_jenis_tujuan_rujukan").select2();

        $("#txt_bpjs_edit_jenis_layanan").select2();

        $("#txt_bpjs_edit_tujuan_poli").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
            ajax: {
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
                    var data = response.response_package.content.response.poli;
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

        $("#txt_bpjs_edit_tipe_rujukan").select2();

        $("#txt_bpjs_edit_diagnosa").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Diagnosa tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_diagnosa",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.diagnosa;
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

        $("#txt_bpjs_edit_tujuan_rujukan").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs-edit"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_faskes_select2",
                type: "GET",
                data: function (term) {
                    return {
                        jenis:$("#txt_bpjs_edit_jenis_tujuan_rujukan").val(),
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content;
                    if(data.metaData.message === "Sukses") {
                        return {
                            results: $.map(data.response.faskes, function (item) {
                                return {
                                    text: item.kode + " - " + item.nama,
                                    id: item.kode
                                }
                            })
                        };
                    } else {
                        /*Swal.fire(
                            "Faskes tidak ditemukan",
                            data.metaData.message,
                            "warning"
                        ).then((result) => {
                            //
                        });*/
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });


        //














        $("body").on("click", ".btnRujuk", function () {
            var target = $(this).attr("target");
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRujukan = id;
            var pasien = $("#pasien_" + id).attr("pasien");
            selectedPasien = pasien;
            if(target === __UIDPENJAMINBPJS__) {

                get_sep_list(pasien);

                $("#txt_bpjs_nomor").val($("#pasien_" + id).attr("no_kartu"));
                $("#txt_bpjs_nik").val($("#pasien_" + id).attr("nik"));
                $("#txt_bpjs_nama").val($("#nama_" + id).html());
                $("#txt_bpjs_telepon").val($("#nama_" + id).attr("kontak"));
                $("#txt_bpjs_rm").val($("#pasien_" + id).html());

                $("#modal-rujuk-bpjs").modal("show");
            }
        });



        function get_sep_list(pasien) {
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_sep_list/" + pasien,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data;
                    $("#target_sep tbody").html("");
                    if(data.length > 0) {
                        for(var sepK in data) {
                            $("#target_sep tbody").append("<tr>" +
                                "<td>" +
                                "<input type=\"radio\" class=\"target_sep\" kode=\"" + data[sepK].uid + "\" value=\"" + data[sepK].sep_no + "\" name=\"sep_" + data[sepK].pasien + "\">" +
                                "</td>" +
                                "<td>" + data[sepK].sep_no + "</td>" +
                                "</tr>");
                        }
                        $("#btnProsesRujuk").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success");
                    } else {
                        $("#target_sep tbody").append("<tr><td colspan=\"2\" class=\"text-danger\"><i class=\"fa fa-exclamation-triangle\"></i> Tidak ada SEP ditemukan</td></tr>");
                        $("#btnProsesRujuk").attr("disabled", "disabled").removeClass("btn-success").addClass("btn-danger");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }


        /*$("#txt_bpjs_nomor_sep").select2({
            minimumInputLength: 1,
            "language": {
                "noResults": function(){
                    return "SEP tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-rujuk-bpjs"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_sep_select2",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content;
                    if(data.metaData.message === "Sukses") {
                        return {
                            results: $.map(data, function (item) {
                                console.log(item);
                                return {
                                    text: item.noSep,
                                    id: item.noSep
                                }
                            })
                        };
                    } else {
                        console.clear();
                        console.log(response);
                        Swal.fire(
                            "SEP tidak ditemukan",
                            data.metaData.message,
                            "warning"
                        ).then((result) => {
                            //
                        });
                    }
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });*/

        $("#btnProsesRujuk").click(function () {
            Swal.fire({
                title: "Proses Rujuk BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var sep_target = $("input.target_sep[type=\"radio\"]:checked");
                    var sep_no = sep_target.val();
                    var sep_uid = sep_target.attr("kode");

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/BPJS",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data: {
                            request: "rujukan_baru",
                            rujukan: currentRujukan,
                            sep: sep_no,
                            sep_uid: sep_uid,
                            pasien: selectedPasien,
                            jenis_faskes: $("#txt_bpjs_jenis_tujuan_rujukan").val(),
                            jenis_faskes_nama: $("#txt_bpjs_jenis_tujuan_rujukan option:selected").text(),
                            tujuan: $("#txt_bpjs_tujuan_rujukan").val(),
                            jenis_pelayanan: $("#txt_bpjs_jenis_layanan").val(),
                            catatan: $("#txt_bpjs_catatan").val(),
                            diagnosa: $("#txt_bpjs_diagnosa").val(),
                            tipe: $("#txt_bpjs_tipe_rujukan").val(),
                            poli: $("#txt_bpjs_tujuan_poli").val(),
                        },
                        type: "POST",
                        success: function (response) {
                            if(parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
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
                                    response.response_package.bpjs.content.metaData.message,
                                    'error'
                                ).then((result) => {
                                    RujukanList.ajax.reload();
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




        $("#btnEditRujuk").click(function () {
            Swal.fire({
                title: "Update Rujukan BPJS?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/BPJS",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data: {
                            request: "rujukan_edit",
                            rujukan: currentRujukanText,
                            rujukan_uid: currentRujukan,
                            pasien: selectedPasien,
                            jenis_faskes: $("#txt_bpjs_edit_jenis_tujuan_rujukan").val(),
                            jenis_faskes_nama: $("#txt_bpjs_edit_jenis_tujuan_rujukan option:selected").text(),
                            tujuan: $("#txt_bpjs_edit_tujuan_rujukan").val(),
                            tujuan_nama: $("#txt_bpjs_edit_tujuan_rujukan option:selected").text(),
                            jenis_pelayanan: $("#txt_bpjs_edit_jenis_layanan").val(),
                            catatan: $("#txt_bpjs_edit_catatan").val(),
                            diagnosa: $("#txt_bpjs_edit_diagnosa").val(),
                            diagnosa_nama: $("#txt_bpjs_edit_diagnosa option:selected").text(),
                            tipe: $("#txt_bpjs_edit_tipe_rujukan").val(),
                            poli: $("#txt_bpjs_edit_tujuan_poli").val(),
                        },
                        type: "POST",
                        success: function (response) {
                            console.clear();
                            console.log(response);
                            if(parseInt(response.response_package.bpjs.content.metaData.code) === 200) {
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
                        error: function (response) {
                            console.clear();
                            console.log(response);
                        }
                    });
                }
            });
        });


        var RujukanLain = $("#table-rujukan-lain").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Rujukan",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "cari_rujukan";
                    d.pasien = selectedPasien;
                    d.sync_data = (($("#check_online").is(":checked")) ? "Y" : "N");
                    d.cari = $("#cari_pasien_bpjs").val();
                },
                dataSrc:function(response) {
                    console.log(response);
                    var data = response.response_package.response_data;
                    if(data === undefined) {
                        data = [];
                    }
                    return data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.asal_rujukan_kode + " - " + row.asal_rujukan_nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.poli_tujuan_kode + " - " + row.poli_tujuan_nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pasien.no_rm + "<br />" +
                            "" + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pelayanan_nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<button class=\"btn btn-info btn-sm detail_rujukan\" id=\"detail_rujukan_" + row.uid + "\"><i class=\"fa fa-eye\"></i> View</button>";
                    }
                }
            ]
        });





        $("body #table-rujukan-lain_filter.dataTables_filter").hide();
        /*$("body #table-rujukan-lain_filter.dataTables_filter input").focus(function () {
            $("#modal-cari-pasien").modal("show");
        });*/

        $("#cari_pasien_bpjs").focus(function () {
            $("#modal-cari-pasien").modal("show");
        });

        $("#modal-cari-pasien").on('shown.bs.modal', function() {
            $("#txt_cari").focus();
        });









            $('#table-list-pencarian').DataTable({
            "bFilter": false,
            "bInfo" : false
        });

        $("#txt_cari").on('keyup', function(){
            params = $("#txt_cari").val();

            $("#table-list-pencarian tbody").html("");
            $("#pencarian-notif").attr("hidden",true);
            $("#loader-search").removeAttr("hidden");
            if (params != ""){
                setTimeout(function() {
                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Antrian/cari-pasien/" + params,
                        type: "GET",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            var MetaData = dataTindakan = response.response_package.response_data;

                            var html = "";

                            if (MetaData !== undefined){
                                $.each(MetaData, function(key, item){
                                    //console.log(item);
                                    var nik = item.nik;
                                    if (nik == null){
                                        nik = '-';
                                    }

                                    var targetBPJS = "";
                                    for(var penj in item.penjamin) {
                                        if(item.penjamin[penj].penjamin === __UIDPENJAMINBPJS__) {
                                            var dataBPJS = JSON.parse(item.penjamin[penj].rest_meta);
                                            targetBPJS = dataBPJS.data.peserta.noKartu;
                                        }
                                    }

                                    var buttonAksi = "<td style='text-align:center;'><button id=\"btn_daftar_pasien_" + item.uid + "\" class=\"btn btn-sm btn-info btnDaftarPasien\" data-toggle=\"tooltip\" title=\"Tambah ke Antrian\"><i class=\"fa fa-user-plus\"></i></button></td>";
                                    if (item.berobat == true){
                                        //buttonAksi = "<td clsas=\"wrap_content\" style=\"text-align:center;\"><span class=\"badge badge-warning\">Sedang Berobat</span></td>";
                                    }

                                    if(targetBPJS === "" || targetBPJS === undefined || targetBPJS === null) {
                                        buttonAksi = "<td></td>";
                                    }



                                    html += "<tr disabled>" +
                                        "<td class=\"wrap_content\">"+ item.autonum  +"</td>" +
                                        "<td>"+ item.no_rm + "[<b id=\"bpjs_" + item.uid + "\">" + targetBPJS  + "</b>]</td>" +
                                        "<td>"+ item.nama +"</td>" +
                                        "<td>"+ nik +"</td>" +
                                        "<td class=\"wrap_content\">"+ item.jenkel +"</td>" +
                                        buttonAksi +
                                        "</tr>";
                                });
                            } else {
                                html += "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
                            }

                            $("#table-list-pencarian tbody").html(html);
                            $("#loader-search").attr("hidden",true);
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });

                }, 250);
            } else {
                $("#loader-search").attr("hidden",true);

                var html = "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
                $("#table-list-pencarian tbody").html(html);
            }

            $("#btnTambahPasien").fadeIn("fast");
        });

        $("body").on("click", ".btnDaftarPasien", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            selectedBPJS = $("#bpjs_" + uid).html().trim();
            selectedPasien = uid;
            //$("body #table-rujukan-lain_filter.dataTables_filter input").val($("#bpjs_" + uid).html().trim());
            $("#cari_pasien_bpjs").val($("#bpjs_" + uid).html().trim());
            $("#modal-cari-pasien").modal("hide");
            RujukanLain.ajax.reload();
            RujukanList.ajax.reload();
        });


    });
</script>



<div id="modal-cari-pasien" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Cari Pasien
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="search-form search-form--light input-group-lg col-md-10">
                            <input type="text" class="form-control" placeholder="Nama / NIK / No. RM" id="txt_cari">
                        </div>
                        <div class="col-md-12" hidden id="pencarian-notif" style="color: red; font-size: 0.8rem;">
                            Mohon ketikkan kata kunci pencarian
                        </div>
                        <div class="col-md-2">
                            <div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered table-striped largeDataType" id="table-list-pencarian">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th class="wrap_content">Jenis Kelamin</th>
                                <th class="wrap_content">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
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
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_telepon" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 col-md-8 mb-4 form-group">
                                    <label for="">Nomor Medical Record (MR)</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                </div>

                                <div class="col-12 col-md-7 form-group">
                                    <label for="">Pilih SEP</label>
                                    <table class="table table-bordered largeDataType" id="target_sep">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content"></th>
                                                <th>SEP</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="col-12 col-md-4 mb-4 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_rujukan"></select>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tujuan_poli"></select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_diagnosa"></select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
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
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-2 mb-2 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-5 mb-5 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_nama" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">Kontak</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_telepon" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                        </div>
                        <div class="card-body row">
                            <div class="col-6">
                                <div class="col-12 col-md-8 mb-4 form-group">
                                    <label for="">Nomor Medical Record (MR)</label>
                                    <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_edit_rm" readonly>
                                </div>
                                <div class="col-12 col-md-4 mb-4 form-group">
                                    <label for="">Jenis Faskes Dirujuk</label>
                                    <select class="form-control uppercase sep" id="txt_bpjs_edit_jenis_tujuan_rujukan">
                                        <option value="1">Puskesmas</option>
                                        <option value="2">Rumah Sakit</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Faskes Dirujuk</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_rujukan"></select>
                                </div>
                                <div class="col-12 col-md-8 form-group">
                                    <label for="">Jenis Pelayanan</label>
                                    <select class="form-control sep" id="txt_bpjs_edit_jenis_layanan">
                                        <option value="2">Rawat Jalan</option>
                                        <option value="1">Rawat Inap</option>
                                    </select>
                                </div>
                                <div class="col-12 form-group">
                                    <label for="">Poli Tujuan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tujuan_poli"></select>
                                </div>
                            </div>

                            <div class="col-6" id="panel-rujukan">
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Tipe Rujukan</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_tipe_rujukan">
                                        <option value="0">Penuh</option>
                                        <option value="1">Partial</option>
                                        <option value="2">Rujuk Balik</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
                                    <label for="">Diagnosa</label>
                                    <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_edit_diagnosa"></select>
                                </div>
                                <div class="col-12 col-md-9 mb-9 form-group" id="group_kelas_rawat">
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
                    <i class="fa fa-save"></i> Edit Rujukan
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>