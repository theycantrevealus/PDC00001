<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function(){

        var currentPenjamin = "";
        var selectedUID = "";

        protocolLib = {
            permintaan_radio_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", parameter, 3000, "hasil_order_radio");
                tableVerifikasiRadiologi.ajax.reload();
                tableAntrianRadiologi.ajax.reload();
            },
            antrian_radiologi_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", parameter, 3000, "hasil_order_radio");
                tableVerifikasiRadiologi.ajax.reload();
                tableAntrianRadiologi.ajax.reload();
            }
        };

        var tableAntrianRadiologi = $("#table-antrian-radiologi").DataTable({
            "ajax":{
                async: false,
                url: __HOSTAPI__ + "/Radiologi/antrian",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    $("#jlh-antrian").html(response.response_package.response_result);
                    return response.response_package.response_data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["waktu_order"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["no_rm"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["pasien"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["departemen"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["dokter"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/radiologi/antrian/" + row.uid + "\" class=\"btn btn-warning btn-sm\">" +
                            "<span><i class=\"fa fa-sign-out-alt\"></i> Detail</span>" +
                            "</a>" +
                            "<button id=\"cetak_" + row.uid + "\" class=\"btn btn-primary btn-sm btnCetak\">" +
                            "<span><i class=\"fa fa-print\"></i>Cetak</span>" +
                            "</button>" +
                            "<button id=\"rad_order_" + row.uid + "\" type='button' class=\"btn btn-success btn-sm btn-selesai-radiologi\" data-toggle='tooltip' title='Tandai selesai'>" +
                            "<span><i class=\"fa fa-check\"></i>Selesai</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });



        $("body").on("click", ".btn-selesai-radiologi", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Orderan selesai akan langsung terkirim pada dokter yang melakukan permintaan pemeriksaan radiologi dan tidak dapat diubah lagi. Mohon pastikan data sudah benar",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Radiologi",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "verifikasi_hasil",
                            uid: uid
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Order Radiologi",
                                    "Pemeriksaan berhasil terkirim",
                                    "success"
                                ).then((result) => {
                                    tableAntrianRadiologi.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Order Radiologi",
                                    "Order gagal diproses",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error:function(response) {
                            //
                        }
                    });
                }
            });
        });






        var tableVerifikasiRadiologi = $("#table-verifikasi-radiologi").DataTable({
            "ajax":{
                async: false,
                url: __HOSTAPI__ + "/Radiologi/verifikasi",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    $("#jlh-antrian").html(response.response_package.response_result);
                    return response.response_package.response_data;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["waktu_order"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["no_rm"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["pasien"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["departemen"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["dokter"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button asesmen=\"" + row.uid_asesmen + "\" id=\"rad_order_" + row.uid + "\" type='button' penjamin=\"" + row.uid_penjamin + "\" class=\"btn btn-info btn-sm btn-verifikasi-radiologi\" data-toggle='tooltip' title=\"Verifikasi Radiologi\"'>" +
                            "<span><i class=\"fa fa-check\"></i>Verifikasi</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });



        $("body").on("click", ".btn-verifikasi-radiologi", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            selectedUID = uid;

            var penjamin = $(this).attr("penjamin");

            var asesmen = $(this).attr("asesmen");
            currentPenjamin = $(this).attr("penjamin");

            $.ajax({
                url: __HOSTAPI__ + "/Radiologi/get-order-detail/" + uid,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    $("#modal-verif-radio").modal("show");
                    var data = response.response_package.response_data;
                    $("#item-verif-radio tbody tr").remove();

                    for(var key in data) {
                        var autoRow = document.createElement("TR");

                        var autoNum = document.createElement("TD");
                        var autoItem = document.createElement("TD");
                        var autoMitra = document.createElement("TD");
                        var autoHarga = document.createElement("TD");

                        $(autoNum).html((parseInt(key) + 1));
                        $(autoItem).html(data[key].tindakan);
                        $(autoMitra).html("<select asesmen=\"" + asesmen + "\" target=\"" + uid + "\" class=\"form-control mitra\" id=\"mitra_" + key + "_" + data[key].uid_tindakan + "\"></select>");
                        $(autoHarga).attr({
                            "id": "harga_" + uid + "_" + data[key].uid_tindakan
                        }).addClass("number_style");







                        $(autoRow).append(autoNum);
                        $(autoRow).append(autoItem);
                        $(autoRow).append(autoMitra);
                        $(autoRow).append(autoHarga);

                        $("#item-verif-radio tbody").append(autoRow);
                    }
                    $("#item-verif-radio tfoot").remove();
                    $("#item-verif-radio").append("<tfoot><tr><td colspan=\"3\" class=\"text-center\"><b>Total</b></td><td class=\"number_style number_style\"></td></tr></tfoot>");

                    $(".mitra").each(function () {
                        var id = $(this).attr("id");
                        var tindakan = id.split("_");
                        tindakan = tindakan[tindakan.length - 1];
                        var asesmen = $(this).attr("asesmen");
                        var target = $(this).attr("target");

                        /*loadMitra(id, tindakan);

                        $("#" + id).select2({
                            dropdownParent: $("#modal-verif-radio")
                        });*/

                        $("#" + id).select2({
                            dropdownParent: $("#modal-verif-radio"),
                            data: loadMitra2("penyedia_order_" + tindakan, tindakan, penjamin),
                            selectOnClose: true,
                            escapeMarkup: function(markup) {
                                return markup;
                            },
                            templateResult: function(data) {
                                return data.html;
                            },
                            templateSelection: function(data) {
                                return data.text;
                            }
                        });

                        loadHarga($("#" + id).val(), asesmen, tindakan, target);
                    });
                },
                error:function(response) {
                    //
                }
            });
            return false;
        });

        $("#btn-verifikasi").click(function () {

            Swal.fire({
                title: "Verifikasi Radiologi",
                text: "Apakah item pemeriksaan sudah benar dan sesuai dengan permintaan?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var forSave = [];
                    $("#item-verif-radio tbody tr").each(function() {
                        var id = $(this).find("td:eq(2) select").attr("id");
                        var tindakan = id.split("_");
                        tindakan = tindakan[tindakan.length - 1];
                        var asesmen = $(this).find("td:eq(2) select").attr("asesmen");
                        var target = $(this).find("td:eq(2) select").attr("target");
                        var harga = $(this).find("td:eq(3)").attr("harga");
                        var mitra = $(this).find("td:eq(2) select").val();


                        forSave.push({
                            request: "verifikasi_item_rad",
                            uid: target,
                            harga: parseFloat(harga),
                            mitra: mitra,
                            tindakan: tindakan,
                            asesmen: asesmen
                        });
                    });

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/Radiologi",
                        type: "POST",
                        data: {
                            request: "verifikasi_item_rad",
                            data_set: forSave,
                            uid: selectedUID
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            if(currentPenjamin === __UIDPENJAMINBPJS__) {
                                push_socket(__ME__, "antrian_radiologi_baru", "*", "Permintaan radiologi", "info").then(function() {
                                    $("#modal-verif-radio").modal("hide");
                                    tableVerifikasiRadiologi.ajax.reload();
                                    tableAntrianRadiologi.ajax.reload();
                                });
                            } else {
                                push_socket(__ME__, "kasir_daftar_baru", "*", "Tagihan Radiologi Baru", "info").then(function() {
                                    $("#modal-verif-radio").modal("hide");
                                    tableVerifikasiRadiologi.ajax.reload();
                                    tableAntrianRadiologi.ajax.reload();
                                });
                            }

                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btnCetak", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var radItem = loadRadOrderDetail(uid);
            var radLampiran = loadRadOrderLampiran(uid);
            var radPasien = loadRadOrderPasien(uid);


            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/rad_hasil.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    rad_pasien: radPasien,
                    rad_item: radItem,
                    rad_lampiran: radLampiran
                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "Laporan Radiologi " + radPasien.pasien.no_rm,
                        afterPrint: function() {
                            //
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });

            return false;
        });

        function loadRadOrderDetail(uid){
            var html;
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Radiologi/get-order-detail/" + uid,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    if (response.response_package.response_result > 0){
                        console.clear();
                        html = "<ol>";
                        dataItem = response.response_package.response_data;
                        $.each(dataItem, function(key, item){
                            html += "<li style=\"border-bottom: dashed 1px #808080; padding: 10px 0;\">" +
                                "<div style=\"margin-left: 10px\">" +
                                "<h4>" + item.tindakan + "</h4>" +
                                "<b>Keterangan:</b><br />" + item.keterangan +
                                "<br />" +
                                "<b>Kesimpulan:</b><br />" + item.kesimpulan +
                                "</div>" +
                                "</li>";
                        });
                        html += "</ol>";
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return html;
        }

        function loadRadOrderLampiran(uid) {
            var MetaData;
            var html = "";
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Radiologi/get-radiologi-lampiran/" + uid,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    MetaData = response.response_package.response_data;
                    for(LampKey in MetaData) {
                        html += "<div class=\"pagebreak\">" +
                            "<embed type=\"application/pdf\" src=\"" + __HOST__ + "document/radiologi/" + MetaData[LampKey].radiologi_order + "/" + MetaData[LampKey].lampiran + "\" width=\"100%\" height=\"100%\" />" +
                            "</div>";
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return html;
        }

        function loadRadOrderPasien(uid) {
            var MetaData;
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Radiologi/get-data-pasien-antrian/" + uid,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    MetaData = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return MetaData;
        }

        function loadHarga(mitra, asesmen, tindakan, target) {
            $("#harga_" + target + "_" + tindakan).html("<b>0.00</b>").attr({
                "harga": 0
            });
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Mitra",
                type: "POST",
                data: {
                    request: "check_target",
                    mitra: mitra,
                    asesmen: asesmen,
                    tindakan: tindakan
                },
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    if(response.response_package.response_data !== undefined && response.response_package.response_data[0] !== undefined) {
                        var harga = response.response_package.response_data[0].harga;
                        if(parseFloat(harga) > 0) {
                            $("#harga_" + target + "_" + tindakan).html("<b>" + number_format(harga, 2, ".", ",") + "</b>").attr({
                                "harga": harga
                            });
                        } else {
                            $("#harga_" + target + "_" + tindakan).html("<b>0.00</b><br /><span class=\"text-warning\"><i class=\"fa fa-info-circle\"></i> Harga bernilai 0. Pastikan harga tindakan sudah benar</span>").attr({
                                "harga": 0
                            });
                        }
                    } else {
                        $("#harga_" + target + "_" + tindakan).html("<b>0.00</b><br /><span class=\"text-warning\"><i class=\"fa fa-info-circle\"></i> Harga bernilai 0. Pastikan harga tindakan sudah benar</span>").attr({
                            "harga": 0
                        });
                    }

                    var totalBiaya = 0;
                    $("#item-verif-radio tbody tr").each(function() {
                        totalBiaya += parseFloat($(this).find("td:eq(3)").attr("harga"));
                    });
                    $("#item-verif-radio tfoot tr td:eq(1)").html("<h4 class=\"text-danger\">" + number_format(totalBiaya, 2, ".", ",") + "</h4>");
                },
                error: function(response) {
                    $("#harga_" + target + "_" + tindakan).html("<b>0.00</b><br /><span class=\"text-warning\"><i class=\"fa fa-info-circle\"></i> Harga bernilai 0. Pastikan harga tindakan sudah benar</span>").attr({
                        "harga": 0
                    });
                }
            });
        }


        $("body").on("change", ".mitra", function() {
            var mitra = $(this).val();
            var tindakan = $(this).attr("id").split("_");
            tindakan = tindakan[tindakan.length - 1];
            var asesmen = $(this).attr("asesmen");
            var target = $(this).attr("target");
            loadHarga(mitra, asesmen, tindakan, target);
            return false;
        });



        function loadMitra(target_ui, itemLab, selected = ""){
            resetSelectBox(target_ui);

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Mitra/mitra_item/RAD/" + itemLab,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){

                    var MetaData = response.response_package.response_data;
                    if (MetaData != "") {
                        $("#" + target_ui + " option").remove();
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            $("#" + target_ui).append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }



        function loadMitra2(target_ui, itemLab, penjamin){
            var MetaData = [];
            var returnedData = [];
            resetSelectBox(target_ui, "Mitra");
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Mitra/mitra_item/RAD/" + itemLab,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    MetaData = response.response_package.response_data;

                    if (MetaData != "" && MetaData !== undefined && MetaData !== null){
                        //$("#" + target_ui + " option").remove();
                        for(i = 0; i < MetaData.length; i++){
                            var target_harga = 0;
                            for(var ai in MetaData[i].harga) {
                                if(MetaData[i].harga[ai].penjamin === penjamin) {
                                    target_harga = MetaData[i].harga[ai].harga;
                                }
                            }

                            returnedData.push({
                                id: MetaData[i].uid,
                                text: "<div class=\"" + ((parseFloat(target_harga) > 0) ? "text-success" : "text-danger") + "\">" + MetaData[i].nama + "</div>",
                                html: "<h6 class=\"" + ((parseFloat(target_harga) > 0) ? "text-success" : "text-danger") + "\">" + MetaData[i].nama + "<b style=\"position: absolute; right: 30px;\" class=\"pull-right\">" + number_format(target_harga, 2, ".", ",") + "</b></h6>",
                                title: MetaData[i].nama
                            });
                            /*var selection = document.createElement("OPTION");


                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama + " - <b>" + number_format(target_harga, 2, ".", ",") + "</b>");
                            $("#" + target_ui).append(selection);*/
                        }
                    } else {
                        returnedData = [];
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return returnedData;
        }





        function resetSelectBox(selector, name) {
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
            $("#" + selector).append(opti_null);
        }

        /*Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            console.log(signalData);
            if(command !== undefined && command !== null && command !== "") {
                protocolLib.command(command, type, parameter, sender, receiver, time);
            } else {
                console.log(command);
            }
        }*/

        setTimeout(function() {

            tableAntrianRadiologi.ajax.reload();
            tableVerifikasiRadiologi.ajax.reload();

        }, 5000);

    });
</script>


<div id="modal-verif-radio" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Detail Permintaan Radiologi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered largeDataType table-striped" id="item-verif-radio">
                            <thead class="thead-dark">
                            <tr>
                                <th class="wrap_content">No</th>
                                <th>Item Radiologi</th>
                                <th style="width: 20%">Mitra</th>
                                <th style="width: 50%">Harga</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-verifikasi" class="btn btn-success">Verifikasi</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>