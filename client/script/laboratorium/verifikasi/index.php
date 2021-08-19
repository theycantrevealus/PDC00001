<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {

        var currentPenjamin = "";
        var currentPoli = "";
        var printMode = false;



        $("body").on("click", ".btnCetak", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var labPasien = loadPasien(uid);
            var labItem = loadLabOrderItem(uid);
            var labLampiran = loadLampiran(uid);

            //console.log(labItem);
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/lab_hasil.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__ : __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    lab_pasien: labPasien,
                    lab_item: labItem,
                    lab_lampiran: labLampiran,
                    tanggal: $("#tanggal_labor_" + uid).html()
                },
                success: function (response) {
                    /*var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1280,height=800,top="+(screen.height-400)+",left="+(screen.width-840));
                    win.document.body.innerHTML = response;*/

                    printMode = true;
                    $(response).printThis({
                        printDelay: 1000,
                        importCSS: true,
                        base: __HOSTNAME__,
                        canvas: true,
                        pageTitle: "Laporan Laboratorium " + labPasien.pasien.no_rm,
                        afterPrint: function() {
                            //
                        }
                    });

                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).on("load", function () {

                        /*if(printMode) {
                            printMode = false;

                        }*/
                    });
                },
                error: function (response) {
                    //
                }
            });

            return false;
        });

        var targettedLabItem;


        $("body").on("click", ".btn-detail-verif", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            var poli = $("#departemen_" + uid).attr("uid");
            currentPoli = poli;

            var penjamin = $(this).attr("penjamin");

            currentPenjamin = penjamin;

            //Get Detail
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Laboratorium/get-laboratorium-order-pack/" + uid,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function (response) {
                    $("#modal-detail-labor").modal("show");
                    targettedLabItem = response.response_package.response_data[0];

                    $(".lab_loader").html(load_laboratorium(targettedLabItem));
                    //$(".target_dpjp").select2();

                    $(".penyedia_order_lab").each(function() {
                        var thisTindakan = $(this).attr("id").split("_");
                        thisTindakan = thisTindakan[thisTindakan.length - 1];





                        $(this).select2({
                            /*dropdownParent: $("#modal-detail-labor"),*/
                            data: loadMitra("penyedia_order_" + thisTindakan, thisTindakan, penjamin),
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


                        var tindakanAttr = $("#penyedia_order_" + thisTindakan).attr("id");
                        tindakanAttr = tindakanAttr[tindakanAttr.length - 1];

                        var asesmenAttr = $("#penyedia_order_" + thisTindakan).attr("asesmen");

                        loadHarga($("#penyedia_order_" + thisTindakan).val(), asesmenAttr, thisTindakan, uid);

                        $("#target_dpjp_lab_" + uid).select2({
                            minimumInputLength: 1,
                            "language": {
                                "noResults": function(){
                                    return "Dokter tidak ditemukan";
                                }
                            },
                            placeholder:"Cari Dokter",
                            cache: true,
                            dropdownParent: $("#container_mitra_" + uid + "_" + thisTindakan),
                            selectOnClose: true,
                            ajax: {
                                dataType: "json",
                                headers:{
                                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                                    "Content-Type" : "application/json",
                                },
                                url:__HOSTAPI__ + "/Pegawai/get_all_dokter_select2",
                                type: "GET",
                                data: function (term) {
                                    return {
                                        search:term.term
                                    };
                                },
                                processResults: function (response) {
                                    var data = response.response_package.response_data;
                                    return {
                                        results: $.map(data, function (item) {
                                            return {
                                                text: item.nama_dokter,
                                                id: item.uid
                                            }
                                        })
                                    };
                                }
                            }
                        }).on("select2:select", function(e) {
                            var data = e.params.data;

                        }).on('results:message', function(params){
                            this.dropdown._resizeDropdown();
                            this.dropdown._positionDropdown();
                        });
                    });
                },
                error: function (response) {
                    //
                }
            });




            return false;
        });

        var tableVerifikasiLabor = $("#table-verifikasi-labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-antrian-backend";
                    d.mode = "reqular";
                    d.status = 'V';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    console.log(response);

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Order"
            },
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
                        return "<span id=\"departemen_" + row.uid + "\" uid=\"" + row.uid_poli + "\">" + row["departemen"] + "</span>";
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
                            "<button penjamin=\"" + row.uid_penjamin + "\" type=\"button\" id=\"order_lab_" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail-verif\" data-toggle='tooltip' title='Detail'>" +
                            "<span><i class=\"fa fa-search\"></i> Detail</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });









        $("body").on("change", ".penyedia_order_lab", function() {
            var mitra = $(this).val();
            var tindakan = $(this).attr("id").split("_");
            tindakan = tindakan[tindakan.length - 1];
            var asesmen = $(this).attr("asesmen");
            var target = $(this).attr("target");
            loadHarga(mitra, asesmen, tindakan, target);
            return false;
        });

        function loadHarga(mitra, asesmen, tindakan, target) {
            $("#harga_" + target + "_" + tindakan).html("<b>Rp. 0.00</b>").attr({
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
                            $("#harga_" + target + "_" + tindakan).html("<b>Rp. " + number_format(harga, 2, ".", ",") + " <span class=\"text-success\"><i class=\"fa fa-check-circle\"></i></span></b>").attr({
                                "harga": harga
                            });
                        } else {
                            $("#harga_" + target + "_" + tindakan).html("<b>Rp. 0.00</b><br /><span class=\"text-warning\"><i class=\"fa fa-info-circle\"></i> Harga bernilai 0. Pastikan harga tindakan sudah benar</span>").attr({
                                "harga": 0
                            });
                        }
                    } else {
                        $("#harga_" + target + "_" + tindakan).html("<b>Rp. 0.00</b><br /><span class=\"text-warning\"><i class=\"fa fa-info-circle\"></i> Harga bernilai 0. Pastikan harga tindakan sudah benar</span>").attr({
                            "harga": 0
                        });
                    }

                    var totalBiaya = 0;
                    $(".harga_iden").each(function () {
                        totalBiaya += parseFloat($(this).attr("harga"));
                    });

                    $("#total_biaya").html("Rp." + number_format(totalBiaya,2, ".", ",")).attr("harga", totalBiaya);
                },
                error: function(response) {
                    $("#harga_" + target + "_" + tindakan).html("<b>Rp. 0.00</b>").attr({
                        "harga": 0
                    });
                }
            });
        }

        function loadMitra(target_ui, itemLab, penjamin){
            var MetaData = [];
            var returnedData = [];
            resetSelectBox(target_ui, "Mitra");
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Mitra/mitra_item/LAB/" + itemLab,
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





        function loadDokter(target_ui, poli, selected = ""){
            resetSelectBox(target_ui, "Dokter");

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + poli,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData != ""){
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");

                            $(selection).attr("value", MetaData[i].dokter).html(MetaData[i].nama);
                            $(target_ui).append(selection);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            })
        }

        function resetSelectBox(selector, name) {
            $("#"+ selector +" option").remove();
            var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
            $("#" + selector).append(opti_null);
        }





        //SOCKET
        /*Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            if(command !== undefined && command !== null && command !== "") {
                protocolLib.command(command, type, parameter, sender, receiver, time);
            } else {
                console.log(command);
            }
        }*/



        protocolLib = {
            antrian_laboratorium_baru: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                notification (type, parameter, 3000, "notif_lab_baru");
            },
            antrian_laboratorium_selesai: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                tableHistoryLabor.ajax.reload();
            },
            permintaan_laboratorium_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", parameter, 3000, "hasil_order_labor");
                tableVerifikasiLabor.ajax.reload();
            }
        };

        $("#btn_verif_all").click(function () {
            var old_html = $(this).html();
            Swal.fire({
                title: "Verifikasi Laboratorium",
                text: "Apakah item pemeriksaan sudah benar dan sesuai dengan permintaan?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".order_item_lab").each(function() {
                        var uid = $(this).attr("target");
                        var tindakan = $(this).attr("tindakan");
                        var pelaksana = $("#penyedia_order_" + tindakan).val();
                        var dpjp = $("#target_dpjp_lab_" + uid).val();
                        var asesmen = $(this).attr("asesmen");
                        var harga = $("#harga_" + uid + "_" + tindakan).attr("harga");

                        if(
                            dpjp !== "" &&
                            dpjp !== null &&
                            dpjp !== undefined &&
                            pelaksana !== ""
                        ) {
                            $.ajax({
                                async: false,
                                url:__HOSTAPI__ + "/Laboratorium",
                                type: "POST",
                                data: {
                                    request: "verifikasi_item_lab",
                                    uid: uid,
                                    dpjp: dpjp,
                                    harga: harga,
                                    mitra: pelaksana,
                                    tindakan: tindakan,
                                    asesmen: asesmen,
                                    poli: currentPoli
                                },
                                beforeSend: function(request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                success: function(response){
                                    Swal.fire(
                                        "Verifikasi Laboratorium",
                                        "Berhasil verifikasi. Arahkan pasien membayar tagihan laboratorium",
                                        "success"
                                    ).then((result) => {
                                        tableVerifikasiLabor.ajax.reload();
                                        if(currentPenjamin === __UIDPENJAMINUMUM__) {
                                            push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya laboratorium baru", "warning").then(function() {
                                                //location.href = __HOSTNAME__ + "/apotek/resep/";
                                            });
                                        } else {
                                            push_socket(__ME__, "antrian_laboratorium_baru", "*", "Permintaan laboratorium baru", "warning").then(function() {
                                                //location.href = __HOSTNAME__ + "/apotek/resep/";
                                            });
                                        }
                                        $("#modal-detail-labor").modal("hide");
                                    });
                                },
                                error: function(response) {
                                    console.log(response);
                                }
                            });
                        }
                    });
                }
            });


            return false;
        });

        $("body").on("click", ".btn_verifikasi_item_lab", function () {
            var uid = $(this).attr("target");

            //$("#verifikasi_lab_container_" + uid).fadeOut();

            var tindakan = $(this).attr("tindakan");
            var pelaksana = $("#penyedia_order_" + tindakan).val();
            var dpjp = $("#target_dpjp_lab_" + uid + "_" + tindakan).val();
            var asesmen = $(this).attr("asesmen");
            var harga = $("#harga_" + uid + "_" + tindakan).attr("harga");

            if(
                dpjp !== "" &&
                dpjp !== null &&
                dpjp !== undefined &&
                pelaksana !== ""
            ) {
                Swal.fire({
                    title: "Verifikasi Laboratorium",
                    text: "Apakah item pemeriksaan sudah benar dan sesuai dengan permintaan?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url:__HOSTAPI__ + "/Laboratorium",
                            type: "POST",
                            data: {
                                request: "verifikasi_item_lab",
                                uid: uid,
                                dpjp: dpjp,
                                harga: harga,
                                mitra: pelaksana,
                                tindakan: tindakan,
                                asesmen: asesmen
                            },
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            success: function(response){

                                $("#verifikasi_lab_container_" + uid + "_" + tindakan).fadeOut(function() {
                                    $("#verifikasi_lab_container_" + uid + "_" + tindakan).remove();
                                    if($(".group_" + uid).length === 0) {
                                        $("#modal-detail-labor").modal("hide");
                                        tableVerifikasiLabor.ajax.reload();
                                    }
                                });

                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                Swal.fire(
                    "Verifikasi Laboratorium",
                    "Data belum lengkap",
                    "error"
                ).then((result) => {
                    //
                });
            }

            return false;
        });





















        function loadPasien(uid_order){		//uid_lab_order
            var MetaData;
            if (uid_order != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-data-pasien-antrian/" + uid_order,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){
                        MetaData = response.response_package;

                        /*if (Object.size(MetaData) > 0){
                            if (MetaData.pasien != ""){
                                $("#no_rm").html(MetaData.pasien.no_rm);
                                $("#tanggal_lahir").html(MetaData.pasien.tanggal_lahir);
                                $("#panggilan").html(MetaData.pasien.panggilan);
                                $("#nama").html(MetaData.pasien.nama);
                                $("#jenkel").html(MetaData.pasien.jenkel);
                            }
                        }*/
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return MetaData;
        }

        function loadLabOrderItem(params){	        //params = id lab_order_detail
            let dataItem;
            let html = "";
            if (params != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-order-detail-item/" + params,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){


                        if (response.response_package.response_result > 0){
                            dataItem = response.response_package.response_data;
                            $.each(dataItem, function(key, item){
                                html += "<div class=\"card\"><div class=\"card-header bg-white\">" +
                                    "<h5 class=\"card-header__title flex m-0\" style=\"padding-top: 20px;\"><i class=\"fa fa-hashtag\"></i> " + (key + 1) + ". "+ item.nama + "</h5>" +
                                    "</div><div class=\"card-body\">" +
                                    "<table class=\"table table-bordered table-striped largeDataType border-style\">" +
                                    "<thead class=\"thead-dark\">" +
                                    "<tr>" +
                                    "<th class=\"wrap_content\">No</th>" +
                                    "<th>Item</th>" +
                                    "<th>Nilai</th>" +
                                    "<th class=\"wrap_content\">Satuan</td>" +
                                    "<th class=\"wrap_content\">Nilai Min.</td>" +
                                    "<th class=\"wrap_content\">Nilai Maks.</td>" +
                                    "</tr>" +
                                    "</thead>" +
                                    "<tbody>";


                                var requestedItem = item.request_item.split(",").map(function(intItem) {
                                    return parseInt(intItem, 10);
                                });

                                if (item.nilai_item.length > 0){
                                    let nomor = 1;
                                    $.each(item.nilai_item, function(key, items){
                                        let nilai = items.nilai;

                                        if (nilai == null){
                                            nilai = "";
                                        }

                                        // id untuk input nilai formatnya: nilai_<uid tindakan>_<id nilai lab>
                                        if(requestedItem.indexOf(items.id_lab_nilai) < 0)
                                        {
                                            /*html += "<tr class=\"strikethrough\">" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td>" + items.keterangan + "</td>" +
                                                "<td><input id=\"nilai_" + items.uid_tindakan + "_" + items.id_lab_nilai + " value=\"" + nilai + "\" class=\"form-control inputItemTindakan\" /></td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";*/
                                        } else {
                                            html += "<tr>" +
                                                "<td>"+ nomor +"</td>" +
                                                "<td style=\"width: 40%;\">" + items.keterangan + "</td>" +
                                                "<td>" + nilai + "</td>" +
                                                "<td>" + items.satuan + "</td>" +
                                                "<td>" + items.nilai_min + "</td>" +
                                                "<td>" + items.nilai_maks + "</td>" +
                                                "</tr>";
                                            nomor++;
                                        }
                                    });
                                }

                                html += "</tbody></table></div></div>";
                                $("#hasil_pemeriksaan").append(html);
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return html;
        }

        function loadLampiran(uid_order) {
            let dataItem;

            if (uid_order != ""){
                $.ajax({
                    async: false,
                    url:__HOSTAPI__ + "/Laboratorium/get-laboratorium-lampiran/" + uid_order,
                    type: "GET",
                    beforeSend: function(request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function(response){
                        if (response.response_package != ""){
                            dataItem = response.response_package.response_data;
                            let baseUrl = __HOST__ + '/document/laboratorium/' + uid_order + '/';

                            /*var pdfjsLib = window['pdfjs-dist/build/pdf'];
                            pdfjsLib.GlobalWorkerOptions.workerSrc = __HOSTNAME__ + '/plugins/pdfjs/build/pdf.worker.js';
                            var loadingTask;*/

                            $(dataItem).each(function(key, item){
                                loadLampiranCanvas(baseUrl + item.lampiran, item.id);
                            });
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }

            return dataItem;
        }

        function loadLampiranCanvas(doc_url, id){
            var newDocRow = document.createElement("TR");

            var newDocCellNum = document.createElement("TD");
            var newDocCellDoc = document.createElement("TD");
            $(newDocCellDoc).addClass("text-center");
            var newDocCellAct = document.createElement("TD");

            var newDocument = document.createElement("CANVAS");

            $(newDocument)
                .css({
                    "width": "75%"
                })
                .attr('id', 'pdfViewer_' + id);

            $(newDocCellDoc).append(newDocument);
            if (doc_url != undefined) {
                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({
                    url: doc_url
                });
                loadingTask.promise.then(function(pdf) {
                    // Fetch the first page
                    var pageNumber = 1;
                    pdf.getPage(pageNumber).then(function(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport({
                            scale: scale
                        });
                        // Prepare canvas using PDF page dimensions
                        var canvas = $(newDocument)[0];
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        // Render PDF page into canvas context
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        var renderTask = page.render(renderContext);
                        renderTask.promise.then(function() {
                            //
                        });
                    });
                }, function(reason) {
                    console.error(reason);
                });
            }

            var newDeleteDoc = document.createElement("button");
            $(newDeleteDoc)
                .addClass("btn btn-sm btn-danger delete_document_registered")
                .html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>")
                .attr('type', 'button')
                .data('id', 'lampiran_' + id);

            $(newDocCellAct).append(newDeleteDoc);

            $(newDocRow).append(newDocCellNum);
            $(newDocRow).append(newDocCellDoc);
            $(newDocRow).append(newDocCellAct);

            $("#labor-lampiran-table").append(newDocRow);
            rebaseLampiran();
        }

        //fungsi untuk tanda ceklis tab lampiran
        /*function check_page_2() {
            if($("#po_document_table tbody tr").length > 0) {
                $("#status-dokumen").fadeIn();
            } else {
                $("#status-dokumen").fadeOut();
            }
        }*/

        function autoDocument(file) {
            var newDocRow = document.createElement("TR");

            var newDocCellNum = document.createElement("TD");
            var newDocCellDoc = document.createElement("TD");
            $(newDocCellDoc).addClass("text-center");
            var newDocCellAct = document.createElement("TD");

            var newDocument = document.createElement("CANVAS");
            $(newDocument).css({
                "width": "75%"
            });
            $(newDocCellDoc).append(newDocument);
            if (file.type == "application/pdf" && file != undefined) {
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({
                        data: pdfData
                    });
                    loadingTask.promise.then(function(pdf) {
                        // Fetch the first page
                        var pageNumber = 1;
                        pdf.getPage(pageNumber).then(function(page) {
                            var scale = 1.5;
                            var viewport = page.getViewport({
                                scale: scale
                            });
                            // Prepare canvas using PDF page dimensions
                            var canvas = $(newDocument)[0];
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            var renderTask = page.render(renderContext);
                            renderTask.promise.then(function() {
                                //
                            });
                        });
                    }, function(reason) {
                        console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }

            var newDeleteDoc = document.createElement("button");
            $(newDeleteDoc).addClass("btn btn-sm btn-danger delete_document").html("<span style=\"display: block;\"><i class=\"fa fa-trash\"></i></span>").attr('type', 'button');
            $(newDocCellAct).append(newDeleteDoc);

            $(newDocRow).append(newDocCellNum);
            $(newDocRow).append(newDocCellDoc);
            $(newDocRow).append(newDocCellAct);

            $("#labor-lampiran-table").append(newDocRow);
            rebaseLampiran();
        }

        function rebaseLampiran() {
            $("#labor-lampiran-table tbody tr").each(function(e) {
                var id = (e + 1);
                $(this).attr({
                    "id": "document_" + id
                });
                $(this).find("td:eq(0)").html((e + 1));
                $(this).find("td:eq(2) button").attr({
                    "id": "delete_document_" + id
                });
            });
        }

        function load_laboratorium(data) {
            var listPetugas = [];
            for(petugasKey in data.petugas) {
                if(data.petugas[petugasKey] !== null) {
                    listPetugas.push(data.petugas[petugasKey].nama);
                }
            }

            data['petugas_parse'] = listPetugas.join(",");

            var returnHTML = "";
            $.ajax({
                url: __HOSTNAME__ + "/pages/laboratorium/lab-single-verif.php",
                async:false,
                data: data,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                success:function(response_html) {
                    returnHTML = response_html;
                },
                error: function(response_html) {
                    console.log(response_html);
                }
            });
            return returnHTML;
        }


        setTimeout(function() {
            //tableServiceLabor.ajax.reload();
            //tableAntrianLabor.ajax.reload();
            //tableHistoryLabor.ajax.reload();
            tableVerifikasiLabor.ajax.reload();
            //tableLab.ajax.reload();

        }, 5000);

    });
</script>









<div id="modal-detail-labor" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Detail Permintaan Laboratorium</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row lab_loader"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_verif_all">Verifikasi</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>