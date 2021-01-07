<script type="text/javascript">
    $(function() {
        function load_resep() {
            var selected = [];
            var resepData = [];
            $.ajax({
                url:__HOSTAPI__ + "/Apotek/selesai",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    console.clear();
                    console.log(response);
                    var resepDataRaw = response.response_package.response_data;
                    for(var resepKey in resepDataRaw) {
                        if(
                            resepDataRaw[resepKey].antrian.departemen != null
                        ) {
                            resepData.push(resepDataRaw[resepKey]);
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return resepData;
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_detail/" + selectedData,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    productData = response.response_package.response_data;
                    for (var a = 0; a < productData.length; a++) {
                        var penjaminList = [];
                        var penjaminListData = productData[a].penjamin;
                        for(var penjaminKey in penjaminListData) {
                            if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                                penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                            }
                        }

                        if(selected.indexOf(productData[a].uid) < 0 && appendData) {
                            $(target).append("<option penjamin-list=\"" + penjaminList.join(",") + "\" satuan-caption=\"" + productData[a].satuan_terkecil.nama + "\" satuan-terkecil=\"" + productData[a].satuan_terkecil.uid + "\" " + ((productData[a].uid == selectedData) ? "selected=\"selected\"" : "") + " value=\"" + productData[a].uid + "\">" + productData[a].nama.toUpperCase() + "</option>");
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            //return (productData.length == selected.length);
            return {
                allow: (productData.length == selected.length),
                data: productData
            };
        }

        function populateObat(data) {
            var obatList = {};
            for(var a = 0; a < data.length; a++) {
                if(data[a].detail != undefined) {
                    var listBiasa = data[a].detail;
                    for(var b = 0; b < listBiasa.length; b++) {
                        if(obatList[listBiasa[b].obat] == undefined) {
                            obatList[listBiasa[b].obat] = {
                                nama: "",
                                counter: 0
                            };
                        }

                        obatList[listBiasa[b].obat]['nama'] = listBiasa[b].detail.nama;
                        obatList[listBiasa[b].obat]['counter'] += 1;
                    }

                    var listRacikan = data[a].racikan;
                    for(var c = 0; c < listRacikan.length; c++) {
                        for(var d = 0; d < listRacikan[c].detail.length; d++) {
                            if(obatList[listRacikan[c].detail[d].obat] == undefined) {
                                obatList[listRacikan[c].detail[d].obat] = {
                                    nama: "",
                                    counter: 0
                                };
                            }

                            obatList[listRacikan[c].detail[d].obat]['nama'] = listRacikan[c].detail[d].detail.nama;
                            obatList[listRacikan[c].detail[d].obat]['counter'] += 1;
                        }
                    }
                }
            }

            return obatList;
        }

        var listResep = load_resep();
        var requiredItem = populateObat(listResep);
        for(var requiredItemKey in requiredItem) {
            $("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase()/* + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b>"*/ + "</li>");
        }

        var tableResep= $("#table-resep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_selesai_backend";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var forReturn = [];
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    for(var dKey in dataSet) {
                        if(dataSet[dKey].departemen !== undefined) {
                            if(dataSet[dKey].departemen.uid !== __POLI_IGD__ && dataSet[dKey].departemen.uid !== __POLI_INAP__) {
                                forReturn.push(dataSet[dKey]);
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return forReturn;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Pasien"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.antrian.departemen.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ((row.antrian.pasien_info.panggilan_name !== undefined && row.antrian.pasien_info.panggilan_name !== null) ? row.antrian.pasien_info.panggilan_name.nama : "") + " " + row.antrian.pasien_info.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.antrian.penjamin_data.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-apotek-panggil\" id=\"panggil_" + row.uid + "\">" +
                                "<i class=\"fa fa-bullhorn\"></i> Panggil" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "P") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<i class=\"fa fa-check\"></i> Terima" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<i class=\"fa fa-check text-success\"></i>";
                        }
                    }
                }
            ]
        });





        var tableResep2 = $("#table-resep-2").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_selesai_backend";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var forReturn = [];
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    for(var dKey in dataSet) {
                        if(dataSet[dKey].departemen !== undefined) {
                            if(dataSet[dKey].departemen.uid !== __POLI_IGD__ && dataSet[dKey].departemen.uid !== __POLI_INAP__) {
                                forReturn.push(dataSet[dKey]);
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return forReturn;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Pasien"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.antrian.departemen.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ((row.antrian.pasien_info.panggilan_name !== undefined && row.antrian.pasien_info.panggilan_name !== null) ? row.antrian.pasien_info.panggilan_name.nama : "") + " " + row.antrian.pasien_info.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.antrian.penjamin_data.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-apotek-panggil\" id=\"panggil_" + row.uid + "\">" +
                                "<i class=\"fa fa-bullhorn\"></i> Panggil" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "P") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<i class=\"fa fa-check\"></i> Terima" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<i class=\"fa fa-check text-success\"></i>";
                        }
                    }
                }
            ]
        });

        var targettedData;

        $("body").on("click", ".btn-apotek-terima", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Obat telah diterima oleh pasien?",
                text: "Pastikan item resep sudah lengkap dan sesuai",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Cek kembali",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Apotek",
                        async:false,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "serah_antrian_selesai",
                            uid: uid
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                tableResep.ajax.reload();
                            } else {
                                console.log(response);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btn-apotek-panggil", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Panggil Pasien?",
                text: "Pastikan item resep sudah lengkap dan sesuai",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Cek kembali",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Apotek",
                        async:false,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "panggil_antrian_selesai",
                            uid: uid
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                tableResep.ajax.reload();
                            } else {
                                console.log(response);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("body").on("click", ".btn-verfikasi", function() {
            var id = $(this).attr("id").split("_");
            var dataRow = id[id.length - 1];
            var resepUID = id[id.length - 2];

            $("#modal-verifikasi").modal("show");
            targettedData = listResep[(dataRow - 1)];
            $("#nama-pasien").attr({
                "set-penjamin": targettedData.antrian.penjamin_data.uid
            }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name !== null)? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");

            loadDetailResep(targettedData);

            $(".obatSelector").select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Inventori/get_item_select2",
                    type: "GET",
                    data: function (term) {
                        return {
                            search:term.term
                        };
                    },
                    cache: true,
                    processResults: function (response) {
                        var sel2ObatData = response.response_package.response_data;
                        var parsedItemDataSel = [];
                        for(var dataObatKey in sel2ObatData)
                        {
                            var selectedBatchResep = refreshBatch(sel2ObatData[dataObatKey].uid);
                            if(selectedBatchResep.length > 0)
                            {
                                parsedItemDataSel.push({
                                    id: sel2ObatData[dataObatKey].uid,
                                    text: "<div style=\"color:" + ((sel2ObatData[dataObatKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + sel2ObatData[dataObatKey].nama.toUpperCase() + "</div>",
                                    html: 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((sel2ObatData[dataObatKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + sel2ObatData[dataObatKey].nama.toUpperCase() + "</div>" +
                                        "<div>" + sel2ObatData[dataObatKey].stok + "</div>" +
                                        "</div>",
                                    title: sel2ObatData[dataObatKey].nama
                                });
                            }
                        }
                        return {
                            results: $.map(parsedItemDataSel, function (item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    html: item.html,
                                    title: item.title
                                }
                            })
                        };
                    }
                },
                //selectOnClose: true,
                placeholder: "Pilih Obat",
                //val: data.detail[a].detail.uid,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            }).on("select2:select", function(e) {
                var data = e.params.data;

                if($(this).hasClass("resep-obat"))
                {
                    var id = $(this).attr("id").split("_");
                    id = id[id.length - 1];
                    calculate_resep(id);
                } else
                {
                    var id = $(this).attr("id").split("_");
                    var group = id[id.length - 2];
                    var item = id[id.length - 1];

                    calculate_racikan(group, item);
                }
            });
        });

        function loadDetailResep(data) {
            $("#load-detail-resep tbody tr").remove();
            for(var a = 0; a < data.detail.length; a++) {
                var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);
                var selectedBatchResep = refreshBatch(data.detail[a].detail.uid);
                var selectedBatchList = [];

                var harga_tertinggi = 0;
                var kebutuhan = parseFloat(data.detail[a].qty);
                for(bKey in selectedBatchResep)
                {
                    if(selectedBatchResep[bKey].harga > harga_tertinggi)    //Selalu ambil harga tertinggi
                    {
                        harga_tertinggi = selectedBatchResep[bKey].harga;
                    }

                    if(kebutuhan > 0)
                    {

                        if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
                        {
                            selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
                        } else {
                            selectedBatchResep[bKey].used = kebutuhan;
                        }
                        kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;

                        selectedBatchList.push(selectedBatchResep[bKey]);
                    }
                }

                if(selectedBatchResep.length >= 0)
                {
                    var profit = 0;
                    var profit_type = "N";

                    if(selectedBatchResep.length > 0) {
                        for(var batchDetail in selectedBatchResep[0].profit)
                        {
                            if(selectedBatchResep[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                            {
                                profit = parseFloat(selectedBatchResep[0].profit[batchDetail].profit);
                                profit_type = selectedBatchResep[0].profit[batchDetail].profit_type;
                            }
                        }
                    }

                    var newDetailRow = document.createElement("TR");
                    $(newDetailRow).attr({
                        "id": "row_resep_" + a,
                        "profit": profit,
                        "profit_type": profit_type
                    });

                    var newDetailCellID = document.createElement("TD");
                    $(newDetailCellID).addClass("text-center").html((a + 1));

                    var newDetailCellObat = document.createElement("TD");
                    var newObat = document.createElement("SELECT");
                    $(newDetailCellObat).append(newObat);
                    $(newObat).attr({
                        "id": "obat_selector_" + a
                    }).addClass("obatSelector resep-obat form-control").select2();
                    $(newObat).append("<option value=\"" + data.detail[a].detail.uid + "\">" + data.detail[a].detail.nama + "</option>").val(data.detail[a].detail.uid).trigger("change");



                    $(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                    $(newDetailCellObat).append("<span id=\"batch_resep_" + a + "\" class=\"selected_batch\"><ol></ol></span>");
                    for(var batchSelKey in selectedBatchList)
                    {
                        $(newDetailCellObat).find("span ol").append("<li batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ")</li>");
                    }

                    $(newDetailCellObat).attr({
                        harga: harga_tertinggi
                    });


                    var newDetailCellSigna = document.createElement("TD");
                    $(newDetailCellSigna).html("<div class=\"input-group mb-3\">" +
                        "<input value=\"" + data.detail[a].signa_qty + "\" type=\"text\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
                        "<div class=\"input-group-prepend\">" +
                        "<span class=\"input-group-text\" id=\"basic-addon1\">&times;</span>" +
                        "</div>" +
                        "<input type=\"text\" value=\"" + data.detail[a].signa_pakai + "\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
                        "</div>");

                    $(newDetailCellSigna).find("input").inputmask({
                        alias: 'decimal',
                        rightAlign: true,
                        placeholder: "0.00",
                        prefix: "",
                        autoGroup: false,
                        digitsOptional: true
                    });

                    var newDetailCellQty = document.createElement("TD");
                    var newQty = document.createElement("INPUT");
                    $(newDetailCellQty).append(newQty);
                    $(newQty).inputmask({
                        alias: "decimal",
                        rightAlign: true,
                        placeholder: "0.00",
                        prefix: "",
                        autoGroup: false,
                        digitsOptional: true
                    }).addClass("form-control qty_resep").attr({
                        "id": "qty_resep_" + a
                    }).val(parseFloat(data.detail[a].qty));

                    var totalObatRaw = parseFloat(harga_tertinggi);
                    var totalObat = 0;
                    if(profit_type === "N")
                    {
                        totalObat = totalObatRaw
                    } else if(profit_type === "P")
                    {
                        totalObat = totalObatRaw + (profit / 100  * totalObatRaw);
                    } else if(profit_type === "A")
                    {
                        totalObat = totalObatRaw + profit;
                    }

                    var newDetailCellHarga = document.createElement("TD");
                    $(newDetailCellHarga).attr({
                        "id": "harga_resep_" + a,
                        "harga": totalObat,
                        "harga_before": parseFloat((selectedBatchResep.length > 0) ? selectedBatchResep[0].harga : 0)
                    }).addClass("text-right number_style").html(number_format(totalObat, 2, ",", "."));

                    var newDetailCellTotal = document.createElement("TD");
                    $(newDetailCellTotal).attr({
                        "id": "total_resep_" + a,
                        "total": parseFloat(data.detail[a].qty) * totalObat
                    }).addClass("text-right number_style").html(number_format(parseFloat(data.detail[a].qty) * totalObat, 2, ",", "."));

                    //=======================================
                    $(newDetailRow).append(newDetailCellID);
                    $(newDetailRow).append(newDetailCellObat);
                    $(newDetailRow).append(newDetailCellSigna);
                    $(newDetailRow).append(newDetailCellQty);
                    $(newDetailRow).append(newDetailCellHarga);
                    $(newDetailRow).append(newDetailCellTotal);
                    $("#load-detail-resep tbody").append(newDetailRow);
                }
            }








            //==================================================================================== RACIKAN
            $("#load-detail-racikan tbody").html("");
            for(var b = 0; b < data.racikan.length; b++) {

                var racikanDetail = data.racikan[b].detail;

                for(var racDetailKey in racikanDetail) {
                    var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                    var selectedBatchListRacikan = [];
                    var harga_tertinggi_racikan = 0;
                    var kebutuhan = parseFloat(data.racikan[b].qty);
                    for(bKey in selectedBatchRacikan)
                    {
                        if(selectedBatchRacikan[bKey].harga > harga_tertinggi_racikan)    //Selalu ambil harga tertinggi
                        {
                            harga_tertinggi_racikan = selectedBatchRacikan[bKey].harga;
                        }

                        if(kebutuhan > 0)
                        {

                            if(kebutuhan > selectedBatchRacikan[bKey].stok_terkini)
                            {
                                selectedBatchRacikan[bKey].used = selectedBatchRacikan[bKey].stok_terkini;
                            } else {
                                selectedBatchRacikan[bKey].used = kebutuhan;
                            }
                            kebutuhan = kebutuhan - selectedBatchRacikan[bKey].stok_terkini;

                            selectedBatchListRacikan.push(selectedBatchRacikan[bKey]);
                        }
                    }

                    if(selectedBatchRacikan.length >= 0)
                    {
                        var profit_racikan = 0;
                        var profit_type_racikan = "N";

                        if(selectedBatchRacikan.length > 0) {
                            for(var batchDetail in selectedBatchRacikan[0].profit)
                            {
                                if(selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                                {
                                    profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                                    profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
                                }
                            }
                        }

                        var newRacikanRow = document.createElement("TR");
                        $(newRacikanRow).addClass("racikan_row").attr({
                            "id": "racikan_group_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid
                        });

                        var newCellRacikanID = document.createElement("TD");
                        var newCellRacikanNama = document.createElement("TD");
                        var newCellRacikanSigna = document.createElement("TD");
                        var newCellRacikanObat = document.createElement("TD");
                        var newCellRacikanJlh = document.createElement("TD");
                        var newCellRacikanHarga = document.createElement("TD");
                        var newCellRacikanTotal = document.createElement("TD");

                        $(newCellRacikanID).attr("rowspan", racikanDetail.length).html(b + 1);
                        $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                        $(newCellRacikanSigna).attr("rowspan", racikanDetail.length).html("<div class=\"input-group mb-3\">" +
                            "<input value=\"" + data.racikan[b].signa_qty + "\" type=\"text\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
                            "<div class=\"input-group-prepend\">" +
                            "<span class=\"input-group-text\" id=\"basic-addon1\">&times;</span>" +
                            "</div>" +
                            "<input type=\"text\" value=\"" + data.racikan[b].signa_pakai + "\" class=\"form-control signa\" placeholder=\"0\" aria-label=\"0\" aria-describedby=\"basic-addon1\" />" +
                            "</div>");
                        $(newCellRacikanJlh).attr("rowspan", racikanDetail.length);

                        var newRacikanObat = document.createElement("SELECT");
                        $(newCellRacikanObat).append(newRacikanObat);

                        var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                        $(newRacikanObat).attr({
                            "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid
                        }).addClass("obatSelector racikan-obat form-control").select2();
                        $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


                        $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                        $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");
                        for(var batchSelKey in selectedBatchListRacikan)
                        {
                            $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ")</li>");
                        }

                        $(newCellRacikanObat).attr({
                            harga: harga_tertinggi_racikan
                        });

                        //$(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch</b><span id=\"batch_racikan_" + a + "\" class=\"selected_batch\" batch=\"" + selectedBatchRacikan[0].batch + "\">[" + selectedBatchRacikan[0].kode + "] " + selectedBatchRacikan[0].expired + "</span>");

                        var totalObatRacikanRaw = parseFloat(harga_tertinggi_racikan);
                        var totalObatRacikan = 0;
                        if(profit_type_racikan === "N")
                        {
                            totalObatRacikan = totalObatRacikanRaw
                        } else if(profit_type_racikan === "P")
                        {
                            totalObatRacikan = totalObatRacikanRaw + (profit_racikan / 100  * totalObatRacikanRaw);
                        } else if(profit_type_racikan === "A")
                        {
                            totalObatRacikan = totalObatRacikanRaw + profit_racikan;
                        }

                        $(newCellRacikanHarga).addClass("text-right number_style").attr({
                            "id": "racikan_harga_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid,
                            "harga": harga_tertinggi_racikan
                        }).html(number_format(totalObatRacikan, 2, ",", "."));

                        $(newCellRacikanTotal).attr({
                            "id": "racikan_total_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid,
                            "total": totalObatRacikan * parseFloat(data.racikan[b].qty)
                        }).addClass("text-right number_style").html(number_format(totalObatRacikan * parseFloat(data.racikan[b].qty), 2, ",", "."));

                        var racikanQty = document.createElement("INPUT");
                        $(newCellRacikanJlh).append(racikanQty);
                        $(racikanQty).attr({
                            "id": "racikan_qty_" + data.racikan[b].uid,
                            "group_racikan": data.racikan[b].uid
                        }).addClass("form-control qty_racikan").val(data.racikan[b].qty).inputmask({
                            alias: 'decimal',
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        });

                        if(racDetailKey < 1) {
                            $(newRacikanRow).append(newCellRacikanID);
                            $(newRacikanRow).append(newCellRacikanNama);
                            $(newRacikanRow).append(newCellRacikanSigna);
                            $(newRacikanRow).append(newCellRacikanJlh);

                            $(newRacikanRow).append(newCellRacikanObat);
                            $(newRacikanRow).append(newCellRacikanHarga);
                            $(newRacikanRow).append(newCellRacikanTotal);
                        } else {
                            $(newRacikanRow).append(newCellRacikanObat);
                            $(newRacikanRow).append(newCellRacikanHarga);
                            $(newRacikanRow).append(newCellRacikanTotal);
                        }

                        $("#load-detail-racikan tbody").append(newRacikanRow);
                    }
                }
            }
        }

        $("body").on("keyup", ".qty_resep", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            calculate_resep(id);
        });

        $("body").on("keyup", ".qty_racikan", function () {
            var id = $(this).attr("id").split("_");
            var group = id[id.length - 1];

            $(".racikan_row[group_racikan=\"" + group + "\"]").each(function() {
                var id = $(this).attr("id").split("_");
                var group = id[id.length - 2];
                var item = id[id.length - 1];

                calculate_racikan(group, item);
            });
        });

        function calculate_racikan(group, id)
        {
            var selectedBatchResep = refreshBatch($("#racikan_obat_" + group + "_" + id).val());

            var selectedBatchListRacikan = [];
            var harga_tertinggi_racikan = 0;
            var qty = $("#racikan_qty_" + group).inputmask("unmaskedvalue");
            var kebutuhan = parseFloat(qty);
            for(bKey in selectedBatchResep)
            {
                if(selectedBatchResep[bKey].harga > harga_tertinggi_racikan)    //Selalu ambil harga tertinggi
                {
                    harga_tertinggi_racikan = selectedBatchResep[bKey].harga;
                }

                if(kebutuhan > 0)
                {

                    if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
                    {
                        selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
                    } else {
                        selectedBatchResep[bKey].used = kebutuhan;
                    }
                    kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;

                    selectedBatchListRacikan.push(selectedBatchResep[bKey]);
                }
            }

            if(selectedBatchResep.length > 0)
            {
                var profit = 0;
                var profit_type = "N";
                for(var batchDetail in selectedBatchResep[0].profit)
                {
                    if(selectedBatchResep[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                    {
                        profit = parseFloat(selectedBatchResep[0].profit[batchDetail].profit);
                        profit_type = selectedBatchResep[0].profit[batchDetail].profit_type;
                    }
                }

                $("#racikan_group_" + group + "_" + id).attr({
                    "profit": profit,
                    "profit_type": profit_type
                });
                $("#racikan_batch_" + group + "_" + id).find("ol li").remove();
                for(var batchSelKey in selectedBatchListRacikan)
                {
                    $("#racikan_batch_" + group + "_" + id).find("ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ")</li>");
                }

                var totalObatRaw = parseFloat(harga_tertinggi_racikan);
                var totalObat = 0;
                if(profit_type === "N")
                {
                    totalObat = totalObatRaw
                } else if(profit_type === "P")
                {
                    totalObat = totalObatRaw + (profit / 100  * totalObatRaw);
                } else if(profit_type === "A")
                {
                    totalObat = totalObatRaw + profit;
                }

                $("#racikan_harga_" + group + "_" + id).html(number_format(parseFloat(totalObat), 2, ",", ".")).attr({
                    "harga": parseFloat(harga_tertinggi_racikan)
                });
                $("#racikan_total_" + group + "_" + id).html(number_format(parseFloat(qty) * totalObat, 2, ",", ".")).attr({
                    "total": parseFloat(qty) * totalObat
                });
            }
        }


        function calculate_resep(id)
        {
            var selectedBatchResep = refreshBatch($("#obat_selector_" + id).val());
            var qty = $("#qty_resep_" + id).inputmask("unmaskedvalue");
            var harga_tertinggi = 0;
            var kebutuhan = parseFloat(qty);
            var selectedBatchList = [];
            for(bKey in selectedBatchResep)
            {
                if(selectedBatchResep[bKey].harga > harga_tertinggi)    //Selalu ambil harga tertinggi
                {
                    harga_tertinggi = selectedBatchResep[bKey].harga;
                }

                if(kebutuhan > 0)
                {

                    if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
                    {
                        selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
                    } else {
                        selectedBatchResep[bKey].used = kebutuhan;
                    }
                    kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;

                    selectedBatchList.push(selectedBatchResep[bKey]);
                }
            }

            if(selectedBatchResep.length > 0)
            {
                var profit = 0;
                var profit_type = "N";
                for(var batchDetail in selectedBatchResep[0].profit)
                {
                    if(selectedBatchResep[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                    {
                        profit = parseFloat(selectedBatchResep[0].profit[batchDetail].profit);
                        profit_type = selectedBatchResep[0].profit[batchDetail].profit_type;
                    }
                }


                $("#row_resep_" + id).attr({
                    "profit": profit,
                    "profit_type": profit_type
                });

                $("#batch_resep_" + id + " ol li").remove();
                for(var batchSelKey in selectedBatchList)
                {
                    $("#batch_resep_" + id + " ol").append("<li batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ")</li>");
                }

                var totalObatRaw = parseFloat(harga_tertinggi);
                var totalObat = 0;
                if(profit_type === "N")
                {
                    totalObat = totalObatRaw
                } else if(profit_type === "P")
                {
                    totalObat = totalObatRaw + (profit / 100  * totalObatRaw);
                } else if(profit_type === "A")
                {
                    totalObat = totalObatRaw + profit;
                }

                $("#harga_resep_" + id).html(number_format(parseFloat(totalObat), 2, ",", ".")).attr({
                    "harga": totalObatRaw
                });
                $("#total_resep_" + id).html(number_format(parseFloat(qty) * totalObat, 2, ",", ".")).attr({
                    "total": parseFloat(qty) * totalObat
                });
            }
        }



        $("#btnProsesResep").click(function() {
            Swal.fire({
                title: 'Verfikasi Resep?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    var UIDResep = targettedData.uid;
                    var detail = [];
                    //Ambil Resep Biasa
                    $("#load-detail-resep tbody tr").each(function() {
                        var profit = parseFloat($(this).attr("profit"));
                        var profit_type = $(this).attr("profit_type");
                        var obat_biasa = $(this).find("td:eq(1) select:eq(0)").val();
                        var batch_biasa = $(this).find("td:eq(1) span.selected_batch ol li:eq(0)").attr("batch");
                        var signa_qty_biasa = parseFloat($(this).find("td:eq(2) input:eq(0)").inputmask("unmaskedvalue"));
                        var signa_pakai_biasa = parseFloat($(this).find("td:eq(2) input:eq(1)").inputmask("unmaskedvalue"));
                        var jumlah_biasa = parseFloat($(this).find("td:eq(3) input").inputmask("unmaskedvalue"));
                        var harga_biasa = parseFloat($(this).find("td:eq(4)").attr("harga"));
                        var penjamin = $("#nama-pasien").attr("set-penjamin");

                        if(signa_qty_biasa > 0 && signa_pakai_biasa > 0 && jumlah_biasa > 0) {
                            var calculateProfit = 0;
                            if(profit_type == "P") {
                                calculateProfit = harga_biasa + (profit / 100 * harga_biasa);
                            } else if(profit_type == "A") {
                                calculateProfit = harga_biasa + profit;
                            } else {
                                calculateProfit = harga_biasa;
                            }

                            detail.push({
                                obat: obat_biasa,
                                batch: batch_biasa,
                                harga: harga_biasa,
                                harga_after_profit: calculateProfit,
                                signa_qty: signa_qty_biasa,
                                signa_pakai: signa_pakai_biasa,
                                jumlah: jumlah_biasa,
                                penjamin:penjamin,
                                profit:profit,
                                profit_type:profit_type
                            });
                        }
                    });

                    var racikan = [];
                    //Ambil Resep Racikan
                    var jumlah_racikan = 0;
                    var signa_qty_racikan = 0;
                    var signa_pakai_racikan = 0;
                    $("#load-detail-racikan tbody tr").each(function(e) {
                        var racikanIdentifier = $(this).attr("id").split("_");
                        var racikanIdentifierID = racikanIdentifier[racikanIdentifier.length - 1];
                        var racikanIdentifierGroup = racikanIdentifier[racikanIdentifier.length - 2];

                        if(e == 0) {
                            var obat_racikan = $(this).find("td:eq(4) select:eq(0)").val();
                            var batch_racikan = $(this).find("td:eq(4) span.selected_batch ol li:eq(0)").attr("batch");
                            signa_qty_racikan = parseFloat($(this).find("td:eq(2) input:eq(0)").inputmask("unmaskedvalue"));
                            signa_pakai_racikan = parseFloat($(this).find("td:eq(2) input:eq(1)").inputmask("unmaskedvalue"));
                            var harga_racikan = parseFloat($(this).find("td:eq(5)").attr("harga"));
                            var bulat_racikan = 1;
                            var decimal_racikan = 1;
                            var ratio_racikan = 1;
                            var pembulatan_racikan = 1;
                            jumlah_racikan = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
                            var total_racikan = $(this).find("td:eq(6)").attr("total");
                        } else {
                            var obat_racikan = $(this).find("td:eq(0) select:eq(0)").val();
                            var batch_racikan = $(this).find("td:eq(0) span.selected_batch ol li:eq(0)").attr("batch");
                            var harga_racikan = parseFloat($(this).find("td:eq(1)").attr("harga"));
                            var bulat_racikan = 1;
                            var decimal_racikan = 1;
                            var ratio_racikan = 1;
                            var pembulatan_racikan = 1;
                            var total_racikan = $(this).find("td:eq(2)").attr("total");
                        }


                        if(batch_racikan !== undefined) {
                            racikan.push({
                                group_racikan: racikanIdentifierGroup,
                                signa_qty:signa_qty_racikan,
                                signa_pakai:signa_pakai_racikan,
                                obat: obat_racikan,
                                batch: batch_racikan,
                                harga: parseFloat(harga_racikan),
                                jumlah: parseFloat(jumlah_racikan),
                                bulat: parseFloat(bulat_racikan),
                                pembulatan: pembulatan_racikan,
                                decimal: parseFloat(decimal_racikan),
                                ratio: parseFloat(ratio_racikan),
                                total:total_racikan
                            });
                        }
                    });



                    $.ajax({
                        url:__HOSTAPI__ + "/Apotek",
                        async:false,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data:{
                            request: "verifikasi_resep",
                            resep: UIDResep,
                            kunjungan:targettedData.kunjungan,
                            pasien:targettedData.pasien,
                            asesmen:targettedData.asesmen,
                            penjamin:targettedData.antrian.penjamin,
                            detail: detail,
                            racikan: racikan
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Verifikasi Berhasil!",
                                    "Silahkan pasien menuju kasir",
                                    "success"
                                ).then((result) => {
                                    tableResep.clear();
                                    tableResep.rows.add(load_resep());
                                    tableResep.draw();
                                    $("#modal-verifikasi").modal("hide");
                                });
                            } else {
                                console.log(response);
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        function refreshBatch(item) {
            var batchData;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + item,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    batchData = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return batchData;
        }
    });
</script>
<div id="modal-verifikasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Verifikasi Resep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="card-header__title flex m-0">Daftar Resep / <span class="text-info" id="nama-pasien"></span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-header card-header-tabs-basic nav" role="tablist">
                            <a href="#tab-resep" class="active" data-toggle="tab" role="tab" aria-controls="keluhan-utama" aria-selected="true">Resep</a>
                            <a href="#tab-racikan" data-toggle="tab" role="tab" aria-selected="false">Racikan</a>
                        </div>
                        <div class="card-body tab-content" id="load-observer">
                            <div class="tab-pane active show fade" id="tab-resep">
                                <table id="load-detail-resep" class="table table-bordered table-striped largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                        <th style="width: 40%;">Obat</th>
                                        <th width="15%">Signa</th>
                                        <th width="15%">Jumlah</th>
                                        <th class="wrap_content">Harga</th>
                                        <th class="wrap_content">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-racikan">
                                <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                        <th style="width: 15%;">Racikan</th>
                                        <th style="width: 15%;">Signa</th>
                                        <th style="width: 15%;">Jumlah</th>
                                        <th>Obat</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesResep"><i class="fa fa-check"></i> Proses</button>
                <button type="button" class="btn btn-info"><i class="fa fa-print"></i> Copy Resep</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>