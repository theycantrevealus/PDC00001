<script type="text/javascript">
    $(function() {
        protocolLib = {
            antrian_apotek_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", "<i class=\"fa fa-info\"></i> " + parameter, 3000, "request_resep");
                tableResep.ajax.reload();
                tableResep2.ajax.reload();
                tableResep3.ajax.reload();
            },
            permintaan_resep_baru: function(protocols, type, parameter, sender, receiver, time) {
                console.clear();

                listResep = load_resep();
                requiredItem = populateObat(listResep);
                for(var requiredItemKey in requiredItem) {
                    $("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase() + "</li>");
                }

                tableResep.clear();
                tableResep.rows.add(load_resep());
                tableResep.draw();
                notification ("info", "<i class=\"fa fa-info\"></i> " + parameter, 3000, "request_resep");
            }
        };
        var selectedDokter = "";
        function load_resep() {
            var selected = [];
            var resepData = [];
            $.ajax({
                url:__HOSTAPI__ + "/Apotek",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
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

        //var listResep = load_resep();
        //var requiredItem = populateObat(listResep);
        /*for(var requiredItemKey in requiredItem) {
            $("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase()/!* + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b>"*!/ + "</li>");
        }*/

        //get_resep_backend
        var tableResep = $("#table-resep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 25, -1], [20, 25, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.request_type = "lunas";
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
                        if(dataSet[dKey].departemen !== undefined && dataSet[dKey].departemen !== null) {
                            forReturn.push(dataSet[dKey]);
                        }
                    }
                    
                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return forReturn;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Kode Amprah"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.departemen.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.pasien_info !== undefined && row.pasien_info !== null) {
                            if(
                                row.pasien_info.panggilan_name !== undefined &&
                                row.pasien_info.panggilan_name !== null
                            ) {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.panggilan_name.nama + " " + row.pasien_info.nama;
                            } else {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.nama;
                            }
                        } else {
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.nama_pasien;
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/apotek/proses/antrian/" + row.uid + "/Rawat Jalan\" class=\"btn btn-info btn-sm\">" +
                            "<span><i class=\"fa fa-check\"></i>Proses</span></a>" +
                            "</div>";
                    }
                }
            ]
        });





        var tableResep2 = $("#table-resep-2").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 25, -1], [20, 25, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.request_type = "igd";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var forReturn = [];
                    var forReturnSelesai = [];
                    var dataSet = response.response_package.response_data;
                    if(dataSet === undefined) {
                        dataSet = [];
                    }



                    var autonum = 1;

                    for(var dKey in dataSet) {
                        if(
                            dataSet[dKey].departemen !== undefined &&
                            dataSet[dKey].departemen !== null
                        ) {
                            if(dataSet[dKey].departemen.uid === __POLI_IGD__ || dataSet[dKey].departemen.uid === __POLI_INAP__) {

                                dataSet[dKey].autonum = autonum;
                                if(dataSet[dKey].status_resep === "D") {
                                    forReturnSelesai.push(dataSet[dKey]);
                                } else {
                                    forReturn.push(dataSet[dKey]);
                                }
                                autonum++;
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return forReturn.concat(forReturnSelesai);
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Kode Amprah"
            },
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
                        if(row.departemen !== null && row.departemen !== undefined) {
                            if(row.departemen.uid === __POLI_INAP__) {
                                if(row.ns_detail !== undefined && row.ns_detail !== null) {
                                    return row.departemen.nama + "<br />" + "<span class=\"text-info\">[" + row.ns_detail.kode_ns + "]</span>" + row.ns_detail.nama_ns;
                                } else {
                                    return "";
                                }
                            } else {
                                return row.departemen.nama;
                            }
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        
                        if(row.pasien_info !== undefined && row.pasien_info !== null) {
                            if(
                                row.pasien_info.panggilan_name !== undefined &&
                                row.pasien_info.panggilan_name !== null
                            ) {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.panggilan_name.nama + " " + row.pasien_info.nama;
                            } else {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.nama;
                            }
                        } else {
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.nama_pasien;
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.status_resep !== "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a href=\"" + __HOSTNAME__ + "/apotek/proses/antrian/" + row.uid + "/IGD\" class=\"btn btn-info btn-sm\">" +
                                "<span><i class=\"fa fa-check\"></i>Proses</span>" +
                                "</a>" +
                                "</div>";
                        } else {
                            return "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Selesai</span>";
                        }
                    }
                }
            ]
        });




        var tableResep3 = $("#table-resep-3").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 25, -1], [20, 25, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.request_type = "inap";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var forReturn = [];
                    var forReturnSelesai = [];
                    var dataSet = response.response_package.response_data;
                    if(dataSet === undefined) {
                        dataSet = [];
                    }



                    var autonum = 1;

                    for(var dKey in dataSet) {
                        if(
                            dataSet[dKey].departemen !== undefined &&
                            dataSet[dKey].departemen !== null
                        ) {
                            if(dataSet[dKey].departemen.uid === __POLI_IGD__ || dataSet[dKey].departemen.uid === __POLI_INAP__) {

                                dataSet[dKey].autonum = autonum;
                                if(dataSet[dKey].status_resep === "D") {
                                    forReturnSelesai.push(dataSet[dKey]);
                                } else {
                                    forReturn.push(dataSet[dKey]);
                                }
                                autonum++;
                            }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return forReturn.concat(forReturnSelesai);
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Kode Amprah"
            },
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
                        if(row.departemen !== null && row.departemen !== undefined) {
                            if (row.departemen.uid === __POLI_INAP__) {
                                if(row.ns_detail !== undefined && row.ns_detail !== null) {
                                    return row.departemen.nama + "<br />" + "<span class=\"text-info\">[" + row.ns_detail.kode_ns + "]</span>" + row.ns_detail.nama_ns;
                                } else {
                                    return "";
                                }
                                
                            } else {
                                return row.departemen.nama;
                            }
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.pasien_info !== undefined && row.pasien_info !== null) {
                            if(
                                row.pasien_info.panggilan_name !== undefined &&
                                row.pasien_info.panggilan_name !== null
                            ) {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.panggilan_name.nama + " " + row.pasien_info.nama;
                            } else {
                                return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.pasien_info.nama;
                            }
                        } else {
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + row.nama_pasien;
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.status_resep !== "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<a href=\"" + __HOSTNAME__ + "/apotek/proses/antrian/" + row.uid + "/Rawat Inap\" class=\"btn btn-info btn-sm\">" +
                                "<span><i class=\"fa fa-check\"></i>Proses</span>" +
                                "</a>" +
                                "</div>";
                        } else {
                            return "<span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Selesai</span>";
                        }
                    }
                }
            ]
        });




        var targettedData = {};

        $("body").on("click", ".btn-verfikasi", function() {
            var id = $(this).attr("id").split("_");
            var dataRow = id[id.length - 1];
            var resepUID = id[id.length - 2];
            selectedDokter = $(this).attr("dokter");

            $.ajax({
                url:__HOSTAPI__ + "/Apotek/detail_resep/" + resepUID,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    targettedData = response.response_package.response_data[0];
                    $("#nama-pasien").attr({
                        "set-penjamin": targettedData.antrian.penjamin_data.uid
                    }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name !== null) ? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");
                    loadDetailResep(targettedData);
                    $("#modal-verifikasi").modal("show");

                },
                error: function(response) {
                    console.log(response);
                }
            });


        });

        function loadDetailResep(data) {
            $("#load-detail-resep tbody tr").remove();

            //==================================================================================== RESEP
            var autoResepNum = 1;
            for(var resepKey in data.detail)
            {
                var resepRow = document.createElement("TR");

                var resepIDCell = document.createElement("TD");
                $(resepIDCell).html(autoResepNum);

                var resepObatCell = document.createElement("TD");
                var resepObat = document.createElement("h5");

                $(resepObatCell).append("<span>" + data.detail[resepKey].detail.nama + " <b>[" + data.detail[resepKey].batch[0].kode + " - " + data.detail[resepKey].batch[0].expired + "]</b>" + "</span>").append(resepObat);
                $(resepObat).addClass("text-info").attr({
                    "id" : "revisi_obat_" + autoResepNum,
                    "uid" : data.detail[resepKey].obat
                });

                var resepSignaCell = document.createElement("TD");
                $(resepSignaCell).html(data.detail[resepKey].signa_qty + " &times; " + data.detail[resepKey].signa_pakai);
                var resepJumlahCell = document.createElement("TD");
                $(resepJumlahCell).html(parseFloat(data.detail[resepKey].qty)).attr({
                    "id" : "qty_resep_" + autoResepNum,
                    "old" : data.detail[resepKey].qty
                }).addClass("number_style");
                /*var resepJumlah = document.createElement("INPUT");
                $(resepJumlahCell).append(resepJumlah);
                $(resepJumlah).attr({
                    "id" : "qty_resep_" + autoResepNum
                }).addClass("form-control resep_qty_change").inputmask({
                    alias: 'decimal', rightAlign: true, placeholder: "0", prefix: "", autoGroup: false, digitsOptional: true
                }).val(parseFloat(data.detail[resepKey].qty));*/
                var resepHargaCell = document.createElement("TD");
                $(resepHargaCell).attr({
                    "id" : "harga_resep_" + autoResepNum
                }).html(data.detail[resepKey].batch[0].harga).addClass("number_style");
                var resepTotalCell = document.createElement("TD");
                $(resepTotalCell).attr({
                    "id" : "total_resep_" + autoResepNum
                }).html(data.detail[resepKey].batch[0].harga * parseFloat(data.detail[resepKey].qty)).addClass("number_style");
                var resepEdit = document.createElement("TD");
                $(resepEdit).html("<i class=\"fa fa-pencil-alt edit-resep-item pull-right\" id=\"ubah_resep_" + autoResepNum + "\"></i>").attr({
                    "id" : "edit_reset_change_" + autoResepNum
                });

                $(resepRow).append(resepIDCell);
                $(resepRow).append(resepObatCell);
                $(resepRow).append(resepSignaCell);
                $(resepRow).append(resepJumlahCell);
                $(resepRow).append(resepHargaCell);
                $(resepRow).append(resepTotalCell);
                $(resepRow).append(resepEdit);
                $("#load-detail-resep tbody").append(resepRow);

                autoResepNum += 1;
            }

            //==================================================================================== RACIKAN

            var autoNumRacikan = 1;
            var autoRacikan = 1;
            for(var racikanKey in data.racikan)
            {
                for(var itemRacikanKey in data.racikan[racikanKey].detail)
                {
                    var racikanRow = document.createElement("TR");
                    $(racikanRow).attr({
                        "uid": data.racikan[racikanKey].uid
                    });

                    var racikanID = document.createElement("TD");
                    $(racikanID).html(autoNumRacikan);
                    var racikanName = document.createElement("TD");
                    var racikanSigna = document.createElement("TD");
                    var racikanObat = document.createElement("TD");
                    $(racikanObat).attr({
                        "id": "racikan_kode_" + autoNumRacikan + "_" + autoRacikan,
                        "uid": data.racikan[racikanKey].detail[itemRacikanKey].detail.uid,
                        "old-name": data.racikan[racikanKey].detail[itemRacikanKey].detail.nama
                    }).html(data.racikan[racikanKey].detail[itemRacikanKey].detail.nama + "<h5 id=\"revisi_racikan_" + autoNumRacikan + "_" + autoRacikan +"\"></h5>");
                    var racikanJumlah = document.createElement("TD");
                    $(racikanJumlah).addClass("number_style").attr({
                        "id": "racikan_jumlah_" + autoNumRacikan,
                        "old": data.racikan[racikanKey].qty
                    }).html(data.racikan[racikanKey].qty);
                    var racikanHarga = document.createElement("TD");
                    var targetHargaRacikan = (data.racikan[racikanKey].detail[itemRacikanKey].batch.length > 0) ? data.racikan[racikanKey].detail[itemRacikanKey].batch[0].harga : 0;
                    $(racikanHarga).attr({
                        "id": "racikan_harga_" + autoNumRacikan + "_" + autoRacikan
                    }).html(targetHargaRacikan).addClass("number_style");
                    var racikanTotal = document.createElement("TD");
                    $(racikanTotal).attr({
                        "id": "racikan_total_" + autoNumRacikan + "_" + autoRacikan
                    }).addClass("number_style").html(parseFloat(targetHargaRacikan) * parseFloat(data.racikan[racikanKey].qty));
                    var racikanAction = document.createElement("TD");
                    $(racikanAction).append("<i class=\"fa fa-pencil-alt racikan_action_edit\" id=\"racikan_action_edit_" + autoNumRacikan + "_" + autoRacikan + "\"></i>").attr({
                        "id": "edit_racikan_change_" + autoNumRacikan + "_" + autoRacikan
                    });

                    if(itemRacikanKey == 0)
                    {
                        $(racikanID).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        });

                        $(racikanName).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        }).html("<span>" + data.racikan[itemRacikanKey].kode + "</span>");

                        $(racikanJumlah).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        });

                        $(racikanSigna).attr({
                            "rowspan": data.racikan[racikanKey].detail.length
                        }).html(data.racikan[itemRacikanKey].signa_qty + " &times; " + data.racikan[itemRacikanKey].signa_pakai);

                        $(racikanRow).append(racikanID);
                        $(racikanRow).append(racikanName);
                        $(racikanRow).append(racikanSigna);
                        $(racikanRow).append(racikanJumlah);
                        $(racikanRow).append(racikanObat);
                        $(racikanRow).append(racikanHarga);
                        $(racikanRow).append(racikanTotal);
                        $(racikanRow).append(racikanAction);
                    } else {
                        $(racikanRow).append(racikanObat);
                        $(racikanRow).append(racikanHarga);
                        $(racikanRow).append(racikanTotal);
                        $(racikanRow).append(racikanAction);
                    }
                    $("#load-detail-racikan tbody").append(racikanRow);
                    autoRacikan++;
                }
                autoNumRacikan ++;
            }
        }


        var targetRevisiRacikan = 0;
        var targetRevisiRacikanItem = 0;
        $("body").on("click", ".racikan_action_edit", function() {
            var target = $(this).attr("id").split("_");
            targetRevisiRacikanItem = target[target.length - 1];
            targetRevisiRacikan = target[target.length - 2];

            $("#modal-ganti-racikan").modal("show");
            $("#target_ganti_racikan").select2({
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
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid
                                }
                            })
                        };
                    }
                }
            });
        });

        $("#btnUbahRacikan").click(function () {
            $("#racikan_action_edit_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).hide();
            //$("#racikan_kode_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html($("#target_ganti_racikan option:selected").text());
            $("#revisi_racikan_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html($("#target_ganti_racikan option:selected").text()).attr({
                "changed": $("#target_ganti_racikan").val()
            });

            if($("#edit_racikan_change_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).find("i.fa-ban").length == 0)
            {
                $("#edit_racikan_change_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).append("<i class=\"fa fa-ban text-danger cancel-racikan-item pull-right\" id=\"batal_racikan_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem + "\"></i>");
            }
            $("#target_ganti_racikan").select2("data", null);
            $("#modal-ganti-racikan").modal("hide");

            //Get Batch item
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + $("#target_ganti_racikan").val(),
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var batchData = response.response_package.response_data;
                    var targetHarga = (response.response_package.response_data.length > 0) ? response.response_package.response_data[0].harga : 0;
                    $("#racikan_harga_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html(targetHarga);
                    $("#racikan_total_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html(parseFloat($("#racikan_jumlah_" + targetRevisiRacikan).html()) * parseFloat(targetHarga));
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("body").on("click", ".cancel-racikan-item", function() {
            var target = $(this).attr("id").split("_");
            targetRevisiRacikanItem = target[target.length - 1];
            targetRevisiRacikan = target[target.length - 2];
            //console.log($("#racikan_kode_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).attr("old-name"));
            //$("#racikan_kode_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html($("#racikan_kode_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).attr("old-name"));

            $("#racikan_action_edit_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).show();
            $("#batal_racikan_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).hide();
            $("#revisi_racikan_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html("");
            $("#racikan_jumlah_" + targetRevisiRacikan).html($("#racikan_jumlah_" + targetRevisiRacikan).attr("old"));

            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + $("#racikan_kode_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).attr("uid"),
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var batchData = response.response_package.response_data;
                    var targetHarga = 0;
                    if(response.response_package.response_data !== null)
                    {
                        targetHarga = (response.response_package.response_data.length > 0) ? batchData[0].harga : 0;
                    } else {
                        targetHarga = 0;
                    }

                    $("#racikan_harga_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html(targetHarga);
                    $("#racikan_total_" + targetRevisiRacikan + "_" + targetRevisiRacikanItem).html(parseFloat($("#racikan_jumlah_" + targetRevisiRacikan).html()) * parseFloat(targetHarga));
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
























        var targetRevisiResep = 0;
        var targettedBatch;

        $("body").on("click", ".edit-resep-item", function() {
            var target = $(this).attr("id").split("_");
            targetRevisiResep = target[target.length - 1];

            $("#modal-ganti-obat").modal("show");
            $("#target_ganti_jumlah").inputmask({
                alias: 'decimal', rightAlign: true, placeholder: "0", prefix: "", autoGroup: false, digitsOptional: true
            }).val(0);
            $("#target_ganti_obat").select2({
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
                        var data = response.response_package.response_data;
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.nama,
                                    id: item.uid
                                }
                            })
                        };
                    }
                }
            });
        });

        $("body").on("click", ".cancel-resep-item", function() {
            var target = $(this).attr("id").split("_");
            targetRevisiResep = target[target.length - 1];

            $("#ubah_resep_" + targetRevisiResep).show();
            $("#batal_resep_" + targetRevisiResep).hide();
            $("#revisi_obat_" + targetRevisiResep).html("");
            $("#qty_resep_" + targetRevisiResep).html($("#qty_resep_" + targetRevisiResep).attr("old"));

            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + $("#revisi_obat_" + targetRevisiResep).attr("uid"),
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var batchData = response.response_package.response_data;
                    var targetHarga = (response.response_package.response_data.length > 0) ? response.response_package.response_data[0].harga : 0;
                    $("#harga_resep_" + targetRevisiResep).html(targetHarga);
                    $("#total_resep_" + targetRevisiResep).html(parseFloat($("#qty_resep_" + targetRevisiResep).html()) * parseFloat(targetHarga));
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnUbahResep").click(function () {
            $("#ubah_resep_" + targetRevisiResep).hide();
            $("#revisi_obat_" + targetRevisiResep).html($("#target_ganti_obat option:selected").text()).attr({
                "changed" : $("#target_ganti_obat").val()
            });

            if($("#edit_reset_change_" + targetRevisiResep).find("i.fa-ban").length == 0)
            {
                $("#edit_reset_change_" + targetRevisiResep).append("<i class=\"fa fa-ban text-danger cancel-resep-item pull-right\" id=\"batal_resep_" + targetRevisiResep + "\"></i>");
            }

            $("#target_ganti_obat").select2("data", null);
            $("#modal-ganti-obat").modal("hide");

            //Get Batch item
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/item_batch/" + $("#target_ganti_obat").val(),
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var batchData = response.response_package.response_data;
                    var targetHarga = 0;
                    if(response.response_package.response_data !== null)
                    {
                        targetHarga = (response.response_package.response_data.length > 0) ? response.response_package.response_data[0].harga : 0;
                        if(response.response_package.response_data.length > 0) {
                            $("#revisi_obat_" + targetRevisiResep).append(" <b uid=\"" + response.response_package.response_data[0].uid + "\">[" + response.response_package.response_data[0].kode + " - " + response.response_package.response_data[0].expired + "]</b>");
                        }
                    } else {
                        targetHarga = 0;
                    }
                    $("#qty_resep_" + targetRevisiResep).html($("#target_ganti_jumlah").inputmask("unmaskedvalue"));
                    $("#harga_resep_" + targetRevisiResep).html(targetHarga);

                    $("#total_resep_" + targetRevisiResep).html($("#target_ganti_jumlah").inputmask("unmaskedvalue") * parseFloat(targetHarga));
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        /*$("body").on("keyup", ".resep_qty_change", function () {
            var target = $(this).attr("id").split("_");
            targetRevisiResep = target[target.length - 1];

            var harga = $("#harga_resep_" + targetRevisiResep).html();
            $("#total_resep_" + targetRevisiResep).html(parseFloat($("#qty_resep_" + targetRevisiResep).html()) * parseFloat(harga));
        });*/


        /*$("body .obatSelector").select2({
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
                    var data = response.response_package.response_data;
                    productData = data;

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
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            var data = e.params.data;

        });*/
















        $("#btnProsesResep").click(function() {
            //console.clear();
            var conf = confirm("Pastikan resep sudah benar sekali lagi. Anda yakin?");
            if(conf) {
                var UIDResep = targettedData.uid;
                var detail = [];
                //Ambil Resep Biasa
                $("#load-detail-resep tbody tr").each(function() {
                    var profit = 0;
                    var profit_type = "N";
                    var obat_biasa = "";
                    if($(this).find("td:eq(1) h5").html() == "") {
                        obat_biasa = $(this).find("td:eq(1) h5").attr("uid");
                    } else {
                        obat_biasa = $(this).find("td:eq(1) h5").attr("changed");
                    }
                    var batch_biasa = "";
                    if($(this).find("td:eq(1) h5 b").length > 0) {
                        batch_biasa = $(this).find("td:eq(1) h5 b").attr("uid");
                    } else {
                        batch_biasa = $(this).find("td:eq(1) span b").attr("uid")
                    }

                    var harga_biasa = parseFloat($(this).find("td:eq(4)").html());
                    var signaSplitter = $(this).find("td:eq(2)").html().split("&times;");
                    var signa_qty_biasa = parseFloat(signaSplitter[0]);
                    var signa_pakai_biasa = parseFloat(signaSplitter[1]);
                    var jumlah_biasa = parseFloat($(this).find("td:eq(3)").html());
                    var penjamin = $(this).find("td:eq(1) select:eq(0) option:selected").attr("penjamin-list");

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
                    /*var racikanIdentifier = $(this).attr("id").split("_");
                    var racikanIdentifierID = racikanIdentifier[racikanIdentifier.length - 1];
                    var racikanIdentifierGroup = racikanIdentifier[racikanIdentifier.length - 2];*/
                    var racikanIdentifierGroup = $(this).attr("uid");
                    if(e == 0) {
                        var obat_racikan = "";
                        if($(this).find("td:eq(4) h5").html() != "")
                        {
                            obat_racikan = $(this).find("td:eq(4) h5").attr("changed")
                        } else {
                            obat_racikan = $(this).find("td:eq(4)").attr("uid")
                        }
                        var batch_racikan = $(this).find("td:eq(3) select:eq(1)").val();
                        signa_qty_racikan = $(this).find("td:eq(2) b:eq(0)").html();
                        signa_pakai_racikan = $(this).find("td:eq(2) b:eq(1)").html();
                        var harga_racikan = $(this).find("td:eq(3) select:eq(1) option:selected").attr("harga");
                        var bulat_racikan = $(this).find("td:eq(4) b:eq(0)").html();
                        var decimal_racikan = $(this).find("td:eq(4) sub").html();
                        var ratio_racikan = $(this).find("td:eq(4) b:eq(1)").html();
                        var pembulatan_racikan = parseFloat($(this).find("td:eq(4) text").html());
                        jumlah_racikan = $(this).find("td:eq(1) input").inputmask("unmaskedvalue");
                        var total_racikan = $(this).find("td:eq(4) span").html();
                    } else {
                        var obat_racikan = $(this).find("td:eq(0) select:eq(0)").val();
                        var batch_racikan = $(this).find("td:eq(0) select:eq(1)").val();
                        var harga_racikan = $(this).find("td:eq(0) select:eq(1) option:selected").attr("harga");
                        var bulat_racikan = $(this).find("td:eq(1) b:eq(0)").html();
                        var decimal_racikan = $(this).find("td:eq(1) sub").html();
                        var ratio_racikan = $(this).find("td:eq(1) b:eq(1)").html();
                        var pembulatan_racikan = parseFloat($(this).find("td:eq(1) text").html());
                        var total_racikan = $(this).find("td:eq(1) span").html();
                    }

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
                        dokter: selectedDokter,
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
                            //tableResep.ajax.reload();
                            tableResep.clear();
                            tableResep.rows.add(load_resep());
                            tableResep.draw();
                            $("#modal-verifikasi").modal("hide");
                        } else {
                            //console.log(response);
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            }
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
                if(protocolLib[command] != undefined) {
                    protocolLib[command](command, type, parameter, sender, receiver, time);
                }
            }
        }*/

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
                        <div class="card-body tab-content">
                            <div class="tab-pane active show fade" id="tab-resep">
                                <table id="load-detail-resep" class="table table-bordered table-striped largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th style="width: 40%;">Obat</th>
                                        <th width="15%">Signa</th>
                                        <th width="15%">Jumlah</th>
                                        <th class="wrap_content">Harga</th>
                                        <th class="wrap_content">Total</th>
                                        <th class="wrap_content"></th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <div class="tab-pane show fade" id="tab-racikan">
                                <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th style="width: 15%;">Racikan</th>
                                        <th>Signa</th>
                                        <th>Jumlah</th>
                                        <th>Obat</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th class="wrap_content" </th>
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

<div id="modal-ganti-obat" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Ubah Obat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 id="target_obat_lama"></h5>
                <div class="form-row">
                    <div class="col-12">
                        <label for="target_ganti_obat">Revisi Obat</label>
                        <select class="form-control" id="target_ganti_obat"></select>
                    </div>
                </div>
                <br />
                <div class="form-row">
                    <div class="col-12">
                        <label for="target_ganti_jumlah">Jumlah</label>
                        <input class="form-control" id="target_ganti_jumlah" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnUbahResep"><i class="fa fa-check"></i> Ubah</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-ganti-racikan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Ubah Obat Racikan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 id="target_obat_lama"></h5>
                <div class="form-row">
                    <div class="col-12">
                        <label for="target_ganti_racikan">Revisi Obat</label>
                        <select class="form-control" id="target_ganti_racikan"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnUbahRacikan"><i class="fa fa-check"></i> Ubah</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>