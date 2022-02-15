<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script src="<?php echo __HOSTNAME__; ?>/plugins/qrcode/qrcode.js"></script>
<script type="text/javascript">
    $(function() {
        protocolLib = {
            resep_selesai_proses: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", parameter, 3000, "notif_pasien_baru");
                tableResep.ajax.reload();
                tableResep2.ajax.reload();
                tableResep3.ajax.reload();
            }
        };
        var targettedUID;
        var targetKodeResep;
        var targetRM;
        var targetNamaPasien;
        var targetTanggalResep;
        var targetHargaTotal;

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

        /*var listResep = load_resep();
        var requiredItem = populateObat(listResep);
        for(var requiredItemKey in requiredItem) {
            $("#required_item_list").append("<li>" + requiredItem[requiredItemKey].nama.toUpperCase()/!* + " <b class=\"text-danger\">" + requiredItem[requiredItemKey].counter + " <i class=\"fa fa-receipt\"></i></b>"*!/ + "</li>");
        }*/

        var tableResep = $("#table-resep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_selesai_backend";
                    d.filter_poli = "rajal";
                    d.request_type = "serah";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var forReturn = [];
                    console.clear();
                    console.log(response);

                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }



                    for(var dKey in dataSet) {
                        if(dataSet[dKey].departemen !== undefined && dataSet[dKey].departemen !== null) {
                            if(dataSet[dKey].departemen.uid !== __POLI_IGD__ && dataSet[dKey].departemen.uid !== __POLI_INAP__) {
                                forReturn.push(dataSet[dKey]);
                            }
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
                searchPlaceholder: "Cari Pasien"
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
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
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
                        return (row.penjamin !== undefined && row.penjamin !== null) ? row.penjamin.nama : "-";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-apotek-panggil\" id=\"panggil_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-bullhorn\"></i> Panggil</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"Rawat Jalan\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "P") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-check\"></i> Terima</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"Rawat Jalan\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
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
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.request_type = "igd_lunas";
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
                            if(
                                dataSet[dKey].departemen !== undefined &&
                                dataSet[dKey].departemen !== null
                            ) {
                                if(dataSet[dKey].departemen.uid === __POLI_IGD__ || dataSet[dKey].departemen.uid === __POLI_INAP__) {
                                    if(dataSet[dKey].status_resep !== "S") {
                                        forReturn.push(dataSet[dKey]);
                                    }
                                }
                            }
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
                searchPlaceholder: "Cari Pasien"
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
                        return (row.departemen !== undefined) ? row.departemen.nama : "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.pasien_info !== undefined && row.pasien_info !== null) {
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
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
                        return (row.penjamin !== undefined && row.penjamin !== null) ? row.penjamin.nama : "-";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        /*if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-apotek-panggil\" id=\"panggil_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-bullhorn\"></i> Panggil</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : "Rawat Inap") + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "P") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-check\"></i> Terima</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : "Rawat Inap") + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<i class=\"fa fa-check text-success\"></i>";
                        }*/

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-check\"></i> Terima</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : "Rawat Inap") + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<i class=\"fa fa-check text-success\"></i>";
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
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.request_type = "inap_lunas";
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
                            if(
                                dataSet[dKey].departemen !== undefined &&
                                dataSet[dKey].departemen !== null
                            ) {
                                if(dataSet[dKey].departemen.uid === __POLI_IGD__ || dataSet[dKey].departemen.uid === __POLI_INAP__) {
                                    if(dataSet[dKey].status_resep !== "S") {
                                        forReturn.push(dataSet[dKey]);
                                    }
                                }
                            }
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
                searchPlaceholder: "Cari Pasien"
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
                        return (row.departemen !== undefined) ? row.departemen.nama : "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.pasien_info !== undefined && row.pasien_info !== null) {
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
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
                        return (row.penjamin !== undefined && row.penjamin !== null) ? row.penjamin.nama : "-";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-check\"></i> Terima</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : "Rawat Inap") + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<i class=\"fa fa-check text-success\"></i>";
                        }
                    }
                }
            ]
        });



        loadPoli("#filter_departemen_riwayat");
        $("#filter_departemen_riwayat").select2();
        $("#filter_departemen_riwayat").change(function() {
            tableResepRiwayat.ajax.reload();
        });


        var tableResepRiwayat = $("#table-resep-history").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d){
                    d.request = "get_resep_backend_v3";
                    d.filter_departemen = $("#filter_departemen_riwayat option:selected").val();
                    d.request_type = "riwayat";
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
                            if(dataSet[dKey].departemen !== null) {
                                if(dataSet[dKey].status_resep === "S") {
                                    forReturn.push(dataSet[dKey]);
                                }
                            }
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
                searchPlaceholder: "Cari Pasien"
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
                            return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
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
                        return (row.penjamin !== undefined && row.penjamin !== null) ? row.penjamin.nama : "-";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"number_style " + ((row.response_min >= __APOTEK_SERVICE_RESPONSE_TIME_TOLERATE__) ? "text-danger" : "text-success") + "\">" + row.response_time + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return "<button id=\"verif_" + row.uid + "_" + row.autonum + "\" class=\"btn btn-sm btn-info btn-verfikasi\"><i class=\"fa fa-check-double\"></i> Verifikasi</button>";

                        if(row.status_resep === "D") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-info btn-sm btn-apotek-panggil\" id=\"panggil_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-bullhorn\"></i> Panggil</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : ((row.departemen.uid === __POLI_INAP__) ? "Rawat Inap" : "Rawat Jalan")) + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "P") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-apotek-terima\" id=\"terima_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-check\"></i> Terima</span>" +
                                "</button>" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : ((row.departemen.uid === __POLI_INAP__) ? "Rawat Inap" : "Rawat Jalan")) + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        } else if(row.status_resep === "S") {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-purple btn-sm btn-apotek-cetak\" jenis=\"" + ((row.departemen.uid === __POLI_IGD__) ? "IGD" : ((row.departemen.uid === __POLI_INAP__) ? "Rawat Inap" : "Rawat Jalan")) + "\" id=\"cetak_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-print\"></i> Cetak</span>" +
                                "</button>" +
                                "</div>";
                        }
                    }
                }
            ]
        });


        function loadPoli(targetDOM, targetted = ""){
            var dataPoli = null;

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Poli/poli-available",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var MetaData = dataPoli = response.response_package.response_data;

                    if (MetaData != ""){ 
                        for(i = 0; i < MetaData.length; i++){
                            var selection = document.createElement("OPTION");
                            $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                            if(MetaData[i].uid !== __POLI_INAP__) {
                                if(targetted !== "") {
                                    if(MetaData[i].uid === targetted) {
                                        $(selection).attr("selected", "selected");
                                    }
                                    $(targetDOM).append(selection);
                                } else {
                                    if(MetaData[i].editable) {
                                        $(targetDOM).append(selection);
                                    }
                                }

                            }
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            return dataPoli;
        }











        var targettedData;

        $("body").on("click", ".btn-apotek-cetak", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            targettedUID = uid;


            var jenis_pasien = $(this).attr("jenis");

            //Load Resep Detail
            $.ajax({
                url:__HOSTAPI__ + "/Apotek/detail_resep_verifikator_2/" + uid,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    targettedData = response.response_package.response_data[0];
                    console.clear();
                    console.log(targettedData);
                    var kajian = targettedData.kajian;
                    for(var kaj in kajian) {
                        $("#hasil_" + kajian[kaj].parameter_kajian).html((kajian[kaj].nilai === "y") ? "<span class=\"text-success wrap_content\"><i class=\"fa fa-check-circle\"></i> Ya</span>" : "<span class=\"text-danger wrap_content\"><i class=\"fa fa-times-circle\"></i> Tidak</span>");
                    }

                    var detail_dokter = targettedData.detail_dokter;
                    var resep_dokter = [];
                    for(var a in detail_dokter) {
                        resep_dokter.push({
                            uid_obat: detail_dokter[a].detail.uid,
                            obat: "<b>R\/</b> " + detail_dokter[a].detail.nama,
                            satuan: detail_dokter[a].detail.satuan_terkecil_info.nama,
                            harga: detail_dokter[a].harga,
                            satuan_konsumsi: detail_dokter[a].satuan_konsumsi,
                            kuantitas: detail_dokter[a].qty,
                            signa: detail_dokter[a].signa_qty + " &times; " + detail_dokter[a].signa_pakai,
                            keterangan: detail_dokter[a].keterangan
                        });
                    }

                    var detail_racikan_dokter = targettedData.racikan;
                    var racikan_dokter = [];
                    for(var b in detail_racikan_dokter) {
                        racikan_dokter.push({
                            racikan: "<b>R\/</b> " + detail_racikan_dokter[b].kode,
                            kuantitas: detail_racikan_dokter[b].qty,
                            satuan_konsumsi: detail_racikan_dokter[b].satuan_konsumsi,
                            signa: detail_racikan_dokter[b].signa_qty + " &times; " + detail_racikan_dokter[b].signa_pakai,
                            keterangan: detail_racikan_dokter[b].keterangan,
                            item: detail_racikan_dokter[b].detail_dokter
                        });
                    }

                    var totalAll = 0;
                    var detail_apotek = targettedData.detail;
                    var resep_apotek = [];
                    var temp_apotek = [];
                    var unique_apotek = {};
                    for(var a in detail_apotek) {
                        if(unique_apotek[detail_apotek[a].detail.uid] === undefined) {
                            unique_apotek[detail_apotek[a].detail.uid] = {
                                detail: detail_apotek[a].detail,
                                pay: detail_apotek[a].pay,
                                obat: "<b>R\/</b> " + detail_apotek[a].detail.nama,
                                satuan: detail_apotek[a].detail.satuan_terkecil_info.nama,
                                kuantitas: 0,
                                signa: detail_apotek[a].signa_qty + " &times; " + detail_apotek[a].signa_pakai,
                                keterangan: detail_apotek[a].keterangan,
                                alasan_ubah: (detail_apotek[a].alasan_ubah !== "" && detail_apotek[a].alasan_ubah !== undefined && detail_apotek[a].alasan_ubah !== null) ? detail_apotek[a].alasan_ubah : "-",
                                harga: (detail_apotek[a].pay[0] !== undefined) ? parseFloat(detail_apotek[a].pay[0].harga) : 0,
                                subtotal: 0
                            };
                        }

                        unique_apotek[detail_apotek[a].detail.uid].kuantitas += parseFloat(detail_apotek[a].qty);
                        //unique_apotek[detail_apotek[a].detail.uid].subtotal += (detail_apotek[a].pay[0] !== undefined) ? parseFloat(detail_apotek[a].pay[0].harga * detail_apotek[a].qty) : 0;
                    }

                    for(var abz in unique_apotek) {
                        temp_apotek.push(unique_apotek[abz]);
                    }

                    detail_apotek = temp_apotek;

                    for(var a in detail_apotek) {
                        resep_apotek.push({
                            obat: "<b>R\/</b> " + detail_apotek[a].obat,
                            satuan: detail_apotek[a].satuan,
                            kuantitas: detail_apotek[a].kuantitas,
                            signa: detail_apotek[a].signa,
                            keterangan: detail_apotek[a].keterangan,
                            alasan_ubah: detail_apotek[a].alasan_ubah,
                            harga: "<h6 class=\"number_style\">" + number_format(detail_apotek[a].harga, 2, ".", ",") + "</h6>",
                            subtotal: "<h6 class=\"number_style\">" + number_format(detail_apotek[a].harga * detail_apotek[a].kuantitas, 2, ".", ",") + "</h6>"
                        });
                        totalAll += parseFloat(detail_apotek[a].harga * detail_apotek[a].kuantitas);
                    }

                    // for(var a in detail_apotek) {
                    //     resep_apotek.push({
                    //         obat: "<b>R\/</b> " + detail_apotek[a].detail.nama,
                    //         satuan: detail_apotek[a].detail.satuan_terkecil_info.nama,
                    //         kuantitas: detail_apotek[a].qty,
                    //         signa: detail_apotek[a].signa_qty + " &times; " + detail_apotek[a].signa_pakai,
                    //         keterangan: detail_apotek[a].keterangan,
                    //         alasan_ubah: (detail_apotek[a].alasan_ubah !== "" && detail_apotek[a].alasan_ubah !== undefined && detail_apotek[a].alasan_ubah !== null) ? detail_apotek[a].alasan_ubah : "-",
                    //         harga: "<h6 class=\"number_style\">" + ((detail_apotek[a].pay[0] !== undefined) ? number_format(parseFloat(detail_apotek[a].pay[0].harga), 2, ".", ",") : number_format(parseFloat(0), 2, ".", ",")) + "</h6>",
                    //         subtotal: "<h6 class=\"number_style\">" + ((detail_apotek[a].pay[0] !== undefined) ? number_format(parseFloat(detail_apotek[a].pay[0].harga * detail_apotek[a].qty), 2, ".", ",") : number_format(parseFloat(0), 2, ".", ",")) + "</h6>"
                    //     });
                    //     totalAll += ((detail_apotek[a].pay[0] !== undefined) ? parseFloat(detail_apotek[a].pay[0].subtotal) : 0);
                    // }


                    var detail_racikan_apotek = targettedData.racikan;
                    var racikan_apotek = [];
                    for(var b in detail_racikan_apotek) {
                        var detailRacikanApotek = detail_racikan_apotek[b].detail;
                        var subtotalRacikanApotek = 0;


                        var prepareRacikanApotek = {
                            kode: "<b>R\/</b> " + detail_racikan_apotek[b].kode,
                            kuantitas: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].jumlah : detail_racikan_apotek[b].qty,
                            signa: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].signa_qty + " &times; " + detail_racikan_apotek[b].change[0].signa_pakai : detail_racikan_apotek[b].signa_qty + " &times; " + detail_racikan_apotek[b].signa_pakai,
                            keterangan: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].keterangan : detail_racikan_apotek[b].keterangan,
                            alasan_ubah: (detail_racikan_apotek[b].change.length > 0) ? detail_racikan_apotek[b].change[0].alasan_ubah : "-",
                            subtotal: 0,
                            detail: []
                        };


                        for(var c in detailRacikanApotek) {
                            if(detail_racikan_apotek[b].change.length > 0) {
                                prepareRacikanApotek.detail.push({
                                    obat: detailRacikanApotek[c].detail.nama,
                                    kuantitas: ((detailRacikanApotek[c].pay[0] !== undefined) ? detailRacikanApotek[c].pay[0].qty : 0),
                                    keterangan: detail_racikan_apotek[b].keterangan,
                                    harga: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].harga), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                                    subtotal: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].subtotal), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                                });
                            } else {
                                prepareRacikanApotek.detail.push({
                                    obat: detailRacikanApotek[c].detail.nama,
                                    kuantitas: ((detailRacikanApotek[c].pay[0] !== undefined) ? detailRacikanApotek[c].pay[0].qty : 0),
                                    signa: detail_racikan_apotek[b].signa_qty + " &times; " + detail_racikan_apotek[b].signa_pakai,
                                    keterangan: detail_racikan_apotek[b].keterangan,
                                    harga: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].harga), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                                    subtotal: "<h6 class=\"number_style\">" + ((detailRacikanApotek[c].pay[0] !== undefined) ? number_format(parseFloat(detailRacikanApotek[c].pay[0].subtotal), 2, ".", ",") : number_format(0, 2, ".", ",")) + "</h6>",
                                });
                            }
                            subtotalRacikanApotek += ((detailRacikanApotek[c].pay[0] !== undefined) ? parseFloat(detailRacikanApotek[c].pay[0].subtotal) : 0);
                            totalAll += parseFloat((detailRacikanApotek[c].pay[0] !== undefined) ? parseFloat(detailRacikanApotek[c].pay[0].subtotal) : 0);
                        }

                        racikan_apotek.push(prepareRacikanApotek);
                    }

                    targetKodeResep = targettedData.kode;
                    targetRM = targettedData.pasien.no_rm;
                    targetNamaPasien = targettedData.pasien.nama;
                    targetTanggalResep = targettedData.created_at_parsed;
                    targetHargaTotal = "Rp. " + number_format(totalAll, 2, ".", ",");

                    var terbilangFinal = "";
                    //Get Terbilang totalAll
                    totalAll = Math.floor(totalAll * 100) / 100;
                    
                    $.ajax({
                        url: __HOSTAPI__ + "/Terminologi",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "parent_terbilang",
                            nilai: totalAll
                        },
                        success: function(response) {
                            terbilangFinal = response.response_package;
                            $.ajax({
                                async: false,
                                url: __HOST__ + "miscellaneous/print_template/resep_view.php",
                                beforeSend: function (request) {
                                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                                },
                                type: "POST",
                                data: {
                                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                                    __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__,
                                    __PC_IDENT__: __PC_IDENT__,
                                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                                    kode: targettedData.kode,
                                    tanggal_resep: targettedData.created_at_parsed,
                                    no_mr: targettedData.pasien.no_rm,
                                    jenis_pasien: jenis_pasien,
                                    nama_pasien: targettedData.pasien.nama,
                                    departemen: (targettedData.antrian.poli_info !== undefined && targettedData.antrian.poli_info !== null) ? targettedData.antrian.poli_info.nama : "Rawat Inap",
                                    tanggal_lahir: targettedData.pasien.tanggal_lahir_parsed,
                                    dokter: targettedData.dokter.nama,
                                    jenis_kelamin: (targettedData.pasien.jenkel_detail !== undefined && targettedData.pasien.jenkel_detail !== null) ? targettedData.pasien.jenkel_detail.nama : "-",
                                    penjamin: targettedData.antrian.penjamin_data.nama,
                                    keterangan_resep: targettedData.keterangan,
                                    keterangan_racikan: targettedData.keterangan_racikan,
                                    alasan_ubah: targettedData.alasan_ubah,
                                    alergi: targettedData.alergi_obat,
                                    sep: (targettedData.antrian.penjamin === __UIDPENJAMINUMUM__) ? "-" : ((targettedData.bpjs !== undefined) ? ((targettedData.bpjs.sep !== undefined) ? targettedData.bpjs.sep : "-") : "-"),
                                    resep_dokter: resep_dokter,
                                    racikan_dokter: racikan_dokter,
                                    resep_apotek: resep_apotek,
                                    racikan_apotek: racikan_apotek,
                                    verifikator: targettedData.verifikator.nama,
                                    total_bayar: "<h6 class=\"number_style\">Rp. " + number_format(totalAll, 2, ".", ",") + "</h6>",
                                    terbilang: titleCase(terbilangFinal)
                                },
                                success: function (response) {
                                    $("#modal-cetak").modal("show");
                                    $("#cetak").html(response);
                                },
                                error: function () {
                                    //
                                }
                            });
                        },
                        error: function(response) {
                            //
                        }
                    });

                    
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnCetakResep").click(function () {
            var dataCetak = $("#target-cetak-resep").html();
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/resep_print.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__.toUpperCase(),
                    __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__.toUpperCase(),
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __PC_IDENT__: __PC_IDENT__,
                    __PC_CUSTOMER_EMAIL__: __PC_CUSTOMER_EMAIL__,
                    __PC_CUSTOMER_ADDRESS_SHORT__: __PC_CUSTOMER_ADDRESS_SHORT__.toUpperCase(),
                    dataCetak: dataCetak
                },
                success: function(response) {
                    var printResepContainer = document.createElement("DIV");
                    $(printResepContainer).html(response);

                    var QRConst = document.createElement("DIV");
                    $(QRConst).qrcode({
                        width: 128,
                        height: 128,
                        text: targetRM + "\n" +
                            targetNamaPasien + "\n" +
                            targetTanggalResep + "\n" +
                            targetHargaTotal + "\n"
                    });

                    var imgcanvas = $(QRConst).find("canvas")[0].toDataURL();
                    $(printResepContainer).find("#qrcodeImage img").attr({
                        src: imgcanvas
                    });

                    /*var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=" + screen.width + ",height=" + screen.height + ",top=0,left=0");
                    win.document.body.innerHTML = $(printResepContainer).html();*/



                    $(printResepContainer).printThis({
                        /*header: null,
                        footer: null,*/
                        pageTitle: targetKodeResep,
                        afterPrint: function() {
                            //
                        }
                    });

                },
                error: function(response) {
                    //
                }
            });
        });





        function loadDetailResep(data) {
            $(".txt_alasan_ubah").html((data.alasan_ubah !== undefined && data.alasan_ubah !== null && data.alasan_ubah !== "") ? data.alasan_ubah : "-");
            $("#txt_keterangan_resep").html((data.keterangan !== undefined && data.keterangan !== null && data.keterangan !== "") ? data.keterangan : "-");
            $("#txt_keterangan_racikan").html((data.keterangan_racikan !== undefined && data.keterangan_racikan !== null && data.keterangan_racikan !== "") ? data.keterangan_racikan : "-");
            $("#load-detail-resep tbody tr").remove();
            for(var a = 0; a < data.detail.length; a++) {
                if(data.detail[a].detail !== null) {
                    var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);
                    var selectedBatchResep = refreshBatch(data.detail[a].detail.uid);
                    //Sini
                    var selectedBatchList = [];

                    var harga_tertinggi = 0;
                    var kebutuhan = parseFloat(data.detail[a].qty);
                    var jlh_sedia = 0;
                    var butuh_amprah = 0;
                    for(bKey in selectedBatchResep)
                    {
                        if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang) {
                            console.log(selectedBatchResep[bKey]);
                            if(selectedBatchResep[bKey].harga > harga_tertinggi)    //Selalu ambil harga tertinggi
                            {
                                harga_tertinggi = parseFloat(selectedBatchResep[bKey].harga);
                            }

                            if(kebutuhan > 0)
                            {

                                if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
                                {
                                    selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
                                } else {
                                    selectedBatchResep[bKey].used = kebutuhan;
                                }
                                kebutuhan -= selectedBatchResep[bKey].stok_terkini;
                                if(selectedBatchResep[bKey].used > 0)
                                {
                                    selectedBatchList.push(selectedBatchResep[bKey]);
                                }
                            }

                            if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang) {
                                jlh_sedia += selectedBatchResep[bKey].stok_terkini;
                            } else {
                                butuh_amprah += selectedBatchResep[bKey].stok_terkini;
                            }
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
                        $(newDetailCellObat).append("<h5 class=\"text-info\">" + data.detail[a].detail.nama + "</h5>");
                        /*$(newObat).attr({
                            "id": "obat_selector_" + a
                        }).addClass("obatSelector resep-obat form-control").select2();
                        $(newObat).append("<option value=\"" + data.detail[a].detail.uid + "\">" + data.detail[a].detail.nama + "</option>").val(data.detail[a].detail.uid).trigger("change");*/



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
                        $(newDetailCellSigna).html("<h5 class=\"text_center wrap_content\">" + data.detail[a].signa_qty + " &times; " + data.detail[a].signa_pakai + "</h5>");

                        $(newDetailCellSigna).find("input").inputmask({
                            alias: 'decimal',
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        }).attr({
                            "disabled": "disabled"
                        });

                        var newDetailCellQty = document.createElement("TD");
                        var newQty = document.createElement("INPUT");
                        var statusSedia = "";

                        $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center wrap_content\">" + parseFloat(data.detail[a].qty) + "</h5>").append(statusSedia);
                        /*$(newQty).inputmask({
                            alias: "decimal",
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        }).addClass("form-control qty_resep").attr({
                            "id": "qty_resep_" + a
                        }).val(parseFloat(data.detail[a].qty));*/

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

                        var newDetailCellKeterangan = document.createElement("TD");
                        $(newDetailCellKeterangan).html(data.detail[a].keterangan);

                        var newDetailCellAlasan = document.createElement("TD");
                        $(newDetailCellAlasan).html((data.detail[a].alasan_ubah !== undefined && data.detail[a].alasan_ubah !== null && data.detail[a].alasan_ubah !== "") ? data.detail[a].alasan_ubah : "-");

                        //=======================================
                        $(newDetailRow).append(newDetailCellID);
                        $(newDetailRow).append(newDetailCellObat);
                        $(newDetailRow).append(newDetailCellSigna);
                        $(newDetailRow).append(newDetailCellQty);
                        $(newDetailRow).append(newDetailCellKeterangan);
                        $(newDetailRow).append(newDetailCellAlasan);

                        $("#load-detail-resep tbody").append(newDetailRow);
                    }
                }
            }



            //==================================================================================== RACIKAN
            $("#load-detail-racikan tbody").html("");
            for(var b = 0; b < data.racikan.length; b++) {
                var racikanDetail = data.racikan[b].detail;
                for(var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                    var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                    var selectedBatchListRacikan = [];
                    var harga_tertinggi_racikan = 0;
                    var kebutuhan_racikan = parseFloat(racikanDetail[racDetailKey].jumlah);
                    var jlh_sedia = 0;
                    var butuh_amprah = 0;
                    for(bKey in selectedBatchRacikan)
                    {
                        if(selectedBatchRacikan[bKey].harga > harga_tertinggi_racikan)    //Selalu ambil harga tertinggi
                        {
                            harga_tertinggi_racikan = selectedBatchRacikan[bKey].harga;
                        }

                        if(kebutuhan_racikan > 0)
                        {

                            if(kebutuhan_racikan > selectedBatchRacikan[bKey].stok_terkini)
                            {
                                selectedBatchRacikan[bKey].used = selectedBatchRacikan[bKey].stok_terkini;
                            } else {
                                selectedBatchRacikan[bKey].used = kebutuhan_racikan;
                            }
                            kebutuhan_racikan -= selectedBatchRacikan[bKey].stok_terkini;

                            selectedBatchListRacikan.push(selectedBatchRacikan[bKey]);
                        }

                        if(selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
                            jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
                        } else {
                            butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
                        }

                    }


                    if(selectedBatchListRacikan.length > 0)
                    {
                        var profit_racikan = 0;
                        var profit_type_racikan = "N";

                        for(var batchDetail in selectedBatchRacikan[0].profit)
                        {
                            if(selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                            {
                                profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                                profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
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
                        var newCellRacikanKeterangan = document.createElement("TD");
                        var newCellRacikanAlasan = document.createElement("TD");

                        $(newCellRacikanID).attr("rowspan", racikanDetail.length).html((b + 1));
                        $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanSigna).addClass("text-center").attr("rowspan", racikanDetail.length).html("<h5 class=\"wrap_content\">" + data.racikan[b].change[0].signa_qty + " &times " + data.racikan[b].change[0].signa_pakai + "</h5>");
                        } else {
                            $(newCellRacikanSigna).addClass("text-center").attr("rowspan", racikanDetail.length).html("<h5 class=\"wrap_content\">" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
                        }
                        $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

                        var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                        var newRacikanObat = document.createElement("SELECT");
                        var statusSediaRacikan = "";

                        $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

                        $(newRacikanObat).attr({
                            "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid
                        }).addClass("obatSelector racikan-obat form-control").select2().attr({
                            "disabled": "disabled"
                        }).prop('disabled', true);
                        $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


                        $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                        $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");
                        for(var batchSelKey in selectedBatchListRacikan)
                        {
                            if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                                $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ")</li>");
                            }
                        }

                        $(newCellRacikanObat).attr({
                            harga: harga_tertinggi_racikan
                        });

                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanJlh).html("<h5 class=\"wrap_content\">" + data.racikan[b].change[0].jumlah + "<h5>");
                        } else {
                            $(newCellRacikanJlh).html("<h5 class=\"wrap_content\">" + data.racikan[b].qty + "<h5>");
                        }

                        //$(newCellRacikanJlh).html("<h5 class=\"wrap_content\">" + data.racikan[b].change[b].jumlah + "<h5>");
                        $(newCellRacikanKeterangan).html(data.racikan[b].keterangan);
                        $(newCellRacikanAlasan).html((data.racikan[b].change.length > 0) ? ((data.racikan[b].change[0].alasan_ubah !== undefined && data.racikan[b].change[0].alasan_ubah !== null && data.racikan[b].change[0].alasan_ubah !== "") ? data.racikan[b].change[0].alasan_ubah : "-") : "-");
                        //alert(b + " - " + racDetailKey);
                        if(racDetailKey === 0) {
                            $(newRacikanRow).append(newCellRacikanID);
                            $(newRacikanRow).append(newCellRacikanNama);
                            $(newRacikanRow).append(newCellRacikanSigna);
                            $(newRacikanRow).append(newCellRacikanJlh);

                            $(newRacikanRow).append(newCellRacikanObat);
                            $(newRacikanRow).append(newCellRacikanKeterangan);
                            $(newRacikanRow).append(newCellRacikanAlasan);
                        } else {
                            $(newRacikanRow).append(newCellRacikanObat);
                        }

                        $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
                        $(newCellRacikanAlasan).attr("rowspan", racikanDetail.length);
                        $("#load-detail-racikan tbody").append(newRacikanRow);
                    } else {
                        console.log("No Batch");
                    }
                }
            }
        }

        function loadDetailResep2(data) {
            $(".txt_alasan_ubah").html((data.alasan_ubah !== undefined && data.alasan_ubah !== null && data.alasan_ubah !== "") ? data.alasan_ubah : "-");
            $("#txt_keterangan_resep").html((data.keterangan !== undefined && data.keterangan !== null && data.keterangan !== "") ? data.keterangan : "-");
            $("#txt_keterangan_racikan").html((data.keterangan_racikan !== undefined && data.keterangan_racikan !== null && data.keterangan_racikan !== "") ? data.keterangan_racikan : "-");
            $("#load-detail-resep tbody tr").remove();

            $("#txt_alasan_ubah").html((data.alasan_ubah !== undefined && data.alasan_ubah !== null && data.alasan_ubah !== "") ? data.alasan_ubah : "-");
            $("#load-detail-resep tbody tr").remove();


            var grouperResep = {};

            var resepVerifikator = data.detail;
            for(var a = 0; a < resepVerifikator.length; a++) {
                if(grouperResep[resepVerifikator[a].item] === undefined) {
                    grouperResep[resepVerifikator[a].item] = {
                        batch: [],
                        alasan_ubah: resepVerifikator[a].alasan_ubah,
                        detail: resepVerifikator[a].detail,
                        keterangan: resepVerifikator[a].keterangan,
                        qty: 0,
                        signa_pakai: resepVerifikator[a].signa_pakai,
                        signa_qty: resepVerifikator[a].signa_qty,
                        verifikator: resepVerifikator[a].verifikator,
                        aturan_pakai: resepVerifikator[a].aturan_pakai
                    };
                }
                resepVerifikator[a].batch.qty = parseFloat(resepVerifikator[a].qty);
                resepVerifikator[a].batch.stok_terkini = parseFloat(resepVerifikator[a].stok_terkini);
                grouperResep[resepVerifikator[a].item].batch.push(resepVerifikator[a].batch);
                grouperResep[resepVerifikator[a].item].qty += parseFloat(resepVerifikator[a].qty);
            }

            var resepAutonum = 1;
            for(var a in grouperResep) {
                var newDetailRow = document.createElement("TR");
                var newDetailCellID = document.createElement("TD");
                $(newDetailCellID).addClass("text-center").html("<h5 class=\"autonum\">" + (resepAutonum) + "</h5>");
                var newDetailCellObat = document.createElement("TD");
                $(newDetailCellObat).append("<h5 class=\"text-info\">" + grouperResep[a].detail.nama + "</h5>");
                $(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                $(newDetailCellObat).append("<span id=\"batch_resep_" + resepAutonum + "\" class=\"selected_batch\"><ol></ol></span>");
                var currentBatch = grouperResep[a].batch;
                for(var b in currentBatch) {
                    $(newDetailCellObat).find("span ol").append("<li class=\"check_batch_avail " + ((currentBatch[b].stok_terkini >= currentBatch[b].qty) ? "text-success" : "text-danger") + "\" batch=\"" + currentBatch[b].uid + "\"><b>[" + currentBatch[b].batch + "]</b> " + currentBatch[b].expired_date_parsed + " (" + currentBatch[b].qty + ")</li>");
                }

                var newDetailCellSigna = document.createElement("TD");
                $(newDetailCellSigna).html("<h5 class=\"text_center wrap_content\">" + grouperResep[a].signa_qty + " &times; " + grouperResep[a].signa_pakai + "</h5>");
                var newDetailCellQty = document.createElement("TD");
                $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center\">" + parseFloat(grouperResep[a].qty) + "</h5>").append("");

                var newDetailCellKeterangan = document.createElement("TD");
                $(newDetailCellKeterangan).css({
                    "white-space": "pre-wrap"
                }).html(grouperResep[a].keterangan);

                var newDetailCellAlasan = document.createElement("TD");
                $(newDetailCellAlasan).html((grouperResep[a].alasan_ubah !== undefined && grouperResep[a].alasan_ubah !== null && grouperResep[a].alasan_ubah !== "") ? grouperResep[a].alasan_ubah : "-");
                $(newDetailRow).append(newDetailCellID);
                $(newDetailRow).append(newDetailCellObat);
                $(newDetailRow).append(newDetailCellSigna);
                $(newDetailRow).append(newDetailCellQty);
                $(newDetailRow).append(newDetailCellKeterangan);
                $(newDetailRow).append(newDetailCellAlasan);

                $("#load-detail-resep tbody").append(newDetailRow);

            }


            // for(var a = 0; a < data.detail.length; a++) {
            //     if(data.detail[a].detail !== null) {
            //         var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);
            //         var selectedBatchResep = refreshBatch(data.detail[a].detail.uid);
            //         var selectedBatchList = [];

            //         var harga_tertinggi = 0;
            //         var kebutuhan = parseFloat(data.detail[a].qty);
            //         var jlh_sedia = 0;
            //         var butuh_amprah = 0;
            //         for(bKey in selectedBatchResep)
            //         {
            //             if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang && parseFloat(selectedBatchResep[bKey].stok_terkini) > 0) {
            //                 if(selectedBatchResep[bKey].harga > harga_tertinggi)    //Selalu ambil harga tertinggi
            //                 {
            //                     harga_tertinggi = parseFloat(selectedBatchResep[bKey].harga);
            //                 }

            //                 if(kebutuhan > 0)
            //                 {

            //                     if(kebutuhan > selectedBatchResep[bKey].stok_terkini)
            //                     {
            //                         selectedBatchResep[bKey].used = selectedBatchResep[bKey].stok_terkini;
            //                     } else {
            //                         selectedBatchResep[bKey].used = kebutuhan;
            //                     }
            //                     kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;
            //                     if(selectedBatchResep[bKey].used > 0)
            //                     {
            //                         selectedBatchList.push(selectedBatchResep[bKey]);
            //                     }
            //                 }

            //                 if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang) {
            //                     jlh_sedia += selectedBatchResep[bKey].stok_terkini;
            //                 } else {
            //                     butuh_amprah += selectedBatchResep[bKey].stok_terkini;
            //                 }

            //             }
            //         }

            //         if(selectedBatchResep.length > 0)
            //         {
            //             var profit = 0;
            //             var profit_type = "N";

            //             for(var batchDetail in selectedBatchResep[0].profit)
            //             {
            //                 if(selectedBatchResep[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
            //                 {
            //                     profit = parseFloat(selectedBatchResep[0].profit[batchDetail].profit);
            //                     profit_type = selectedBatchResep[0].profit[batchDetail].profit_type;
            //                 }
            //             }

            //             var newDetailRow = document.createElement("TR");
            //             $(newDetailRow).attr({
            //                 "id": "row_resep_" + a,
            //                 "profit": profit,
            //                 "profit_type": profit_type
            //             });

            //             var newDetailCellID = document.createElement("TD");
            //             $(newDetailCellID).addClass("text-center").html("<h5 class=\"autonum\">" + (a + 1) + "</h5>");

            //             var newDetailCellObat = document.createElement("TD");
            //             var newObat = document.createElement("SELECT");
            //             $(newDetailCellObat).append("<h5 class=\"text-info\">" + data.detail[a].detail.nama + "</h5>");
            //             /*$(newObat).attr({
            //                 "id": "obat_selector_" + a
            //             }).addClass("obatSelector resep-obat form-control").select2();
            //             $(newObat).append("<option value=\"" + data.detail[a].detail.uid + "\">" + data.detail[a].detail.nama + "</option>").val(data.detail[a].detail.uid).trigger("change");*/



            //             $(newDetailCellObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
            //             $(newDetailCellObat).append("<span id=\"batch_resep_" + a + "\" class=\"selected_batch\"><ol></ol></span>");
            //             for(var batchSelKey in selectedBatchList)
            //             {
            //                 $(newDetailCellObat).find("span ol").append("<li batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ")</li>");
            //             }

            //             $(newDetailCellObat).attr({
            //                 harga: harga_tertinggi
            //             });

            //             var newDetailCellSigna = document.createElement("TD");
            //             $(newDetailCellSigna).html("<h5 class=\"text_center wrap_content\">" + data.detail[a].signa_qty + " &times; " + data.detail[a].signa_pakai + "</h5>");

            //             $(newDetailCellSigna).find("input").inputmask({
            //                 alias: 'decimal',
            //                 rightAlign: true,
            //                 placeholder: "0.00",
            //                 prefix: "",
            //                 autoGroup: false,
            //                 digitsOptional: true
            //             });

            //             var newDetailCellQty = document.createElement("TD");
            //             var newQty = document.createElement("INPUT");
            //             var statusSedia = "";

            //             /*if(parseFloat(data.detail[a].qty) <= parseFloat(data.detail[a].sedia))
            //             {
            //                     statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + data.detail[a].sedia + "</b>";
            //                 } else {
            //                 statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + data.detail[a].sedia + "</b>";
            //             }*/
            //             if(parseFloat(data.detail[a].qty) <= parseFloat(jlh_sedia))
            //             {
            //                 statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            //             } else {
            //                 statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
            //             }

            //             if((parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia)) > 0) {
            //                 statusSedia += "<br /><b class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i>Butuh Amprah : " + number_format(parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</b>";

            //                 if(currentStatusOpname === "A") {
            //                     $("#btnSelesai").attr({
            //                         "disabled": "disabled"
            //                     }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
            //                 } else {
            //                     $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
            //                 }

            //             } else {
            //                 var disabledStatus = $("#btnSelesai").attr('name');
            //                 if (typeof attr !== typeof undefined && attr !== false) {
            //                     // ...
            //                 } else {
            //                     $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
            //                 }
            //             }

            //             $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center\">" + parseFloat(data.detail[a].qty) + "</h5>").append(statusSedia);
            //             /*$(newQty).inputmask({
            //                 alias: "decimal",
            //                 rightAlign: true,
            //                 placeholder: "0.00",
            //                 prefix: "",
            //                 autoGroup: false,
            //                 digitsOptional: true
            //             }).addClass("form-control qty_resep").attr({
            //                 "id": "qty_resep_" + a
            //             }).val(parseFloat(data.detail[a].qty));*/

            //             var totalObatRaw = parseFloat(harga_tertinggi);
            //             var totalObat = 0;
            //             if(profit_type === "N")
            //             {
            //                 totalObat = totalObatRaw
            //             } else if(profit_type === "P")
            //             {
            //                 totalObat = totalObatRaw + (profit / 100  * totalObatRaw);
            //             } else if(profit_type === "A")
            //             {
            //                 totalObat = totalObatRaw + profit;
            //             }

            //             var newDetailCellKeterangan = document.createElement("TD");
            //             $(newDetailCellKeterangan).css({
            //                 "white-space": "pre-wrap"
            //             }).html(data.detail[a].keterangan);

            //             var newDetailCellAlasan = document.createElement("TD");
            //             $(newDetailCellAlasan).html((data.detail[a].alasan_ubah !== undefined && data.detail[a].alasan_ubah !== null && data.detail[a].alasan_ubah !== "") ? data.detail[a].alasan_ubah : "-");
            //             //=======================================
            //             $(newDetailRow).append(newDetailCellID);
            //             $(newDetailRow).append(newDetailCellObat);
            //             $(newDetailRow).append(newDetailCellSigna);
            //             $(newDetailRow).append(newDetailCellQty);
            //             $(newDetailRow).append(newDetailCellKeterangan);
            //             $(newDetailRow).append(newDetailCellAlasan);

            //             $("#load-detail-resep tbody").append(newDetailRow);
            //         }
            //     }
            // }








            //==================================================================================== RACIKAN
            //Checker
            for(var b = 0; b < data.racikan.length; b++) {
                var racikanDetail = data.racikan[b].detail;
                for (var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                    var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                    var kebutuhan_racikan = parseFloat(racikanDetail[racDetailKey].jumlah);
                    var jlh_sedia = 0;
                    var butuh_amprah = 0;
                    for(bKey in selectedBatchRacikan) {
                        if(kebutuhan_racikan > 0) {
                            if(selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
                                jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
                            } else {
                                butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
                            }
                        }
                    }
                }
            }


            $("#load-detail-racikan tbody").html("");
            for(var b = 0; b < data.racikan.length; b++) {
                var uniqueObatRacikan = {};

                
                var racikanDetail = data.racikan[b].detail;

                for(var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                    if(uniqueObatRacikan[racikanDetail[racDetailKey].obat] === undefined) {
                        uniqueObatRacikan[racikanDetail[racDetailKey].obat] = {
                            batch: [],
                            detail: racikanDetail[racDetailKey].detail,
                            id: racikanDetail[racDetailKey].id,
                            jumlah: 0,
                            stok_terkini: racikanDetail[racDetailKey].stok_terkini,
                            kekuatan: racikanDetail[racDetailKey].kekuatan,
                            obat: racikanDetail[racDetailKey].obat,
                            pay: racikanDetail[racDetailKey].pay
                        };
                    }

                    uniqueObatRacikan[racikanDetail[racDetailKey].obat].jumlah += parseFloat(racikanDetail[racDetailKey].jumlah);
                    racikanDetail[racDetailKey].batch.jumlah = parseFloat(racikanDetail[racDetailKey].jumlah);
                    racikanDetail[racDetailKey].batch.stok_terkini = parseFloat(racikanDetail[racDetailKey].stok_terkini);
                    uniqueObatRacikan[racikanDetail[racDetailKey].obat].batch.push(racikanDetail[racDetailKey].batch);
                }

                racikanDetail = [];

                for(var ban in uniqueObatRacikan) {
                    racikanDetail.push(uniqueObatRacikan[ban]);
                }




                for(var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                    var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                    var selectedBatchListRacikan = [];
                    var selectedBatchListRacikanAmprah = racikanDetail[racDetailKey].batch;
                    var harga_tertinggi_racikan = 0;
                    //var kebutuhan_racikan = parseFloat(data.racikan[b].qty);
                    var kebutuhan_racikan = parseFloat(racikanDetail[racDetailKey].jumlah);
                    var jlh_sedia = 0;
                    var butuh_amprah = 0;
                    // for(bKey in selectedBatchRacikan)
                    // {
                    //     if(selectedBatchRacikan[bKey].harga > harga_tertinggi_racikan)    //Selalu ambil harga tertinggi
                    //     {
                    //         harga_tertinggi_racikan = selectedBatchRacikan[bKey].harga;
                    //     }

                    //     if(kebutuhan_racikan > 0)
                    //     {

                    //         if(kebutuhan_racikan > selectedBatchRacikan[bKey].stok_terkini)
                    //         {
                    //             selectedBatchRacikan[bKey].used = selectedBatchRacikan[bKey].stok_terkini;
                    //         } else {
                    //             selectedBatchRacikan[bKey].used = kebutuhan_racikan;
                    //         }



                    //         if(selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
                    //             kebutuhan_racikan -= selectedBatchRacikan[bKey].stok_terkini;
                    //             jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
                    //             selectedBatchListRacikan.push(selectedBatchRacikan[bKey]);
                    //         } else {
                    //             butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
                    //             selectedBatchListRacikanAmprah.push(selectedBatchRacikan[bKey]);
                    //         }
                    //     }
                    // }


                    if(selectedBatchListRacikan.length > 0) {
                        var profit_racikan = 0;
                        var profit_type_racikan = "N";

                        for(var batchDetail in selectedBatchRacikan[0].profit)
                        {
                            if(selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                            {
                                profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                                profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
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
                        var newCellRacikanKeterangan = document.createElement("TD");
                        var newCellRacikanAlasan = document.createElement("TD");

                        $(newCellRacikanID).attr("rowspan", racikanDetail.length).html("<h5 class=\"autonum\">" + (b + 1) + "</h5>");
                        $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].change[0].signa_qty + " &times " + data.racikan[b].change[0].signa_pakai + "</h5>");
                        } else {
                            $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
                        }

                        $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

                        var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                        var newRacikanObat = document.createElement("SELECT");
                        var statusSediaRacikan = "";
                        /*if(parseFloat(racikanDetail[racDetailKey].jumlah) <= parseFloat(racikanDetail[racDetailKey].sedia))
                        {
                            statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                        } else {
                            statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                        }*/

                        if(parseFloat(data.racikan[b].qty) <= parseFloat(jlh_sedia))
                        {
                            //statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        } else {
                            //statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        }

                        /*if((parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia)) > 0) {
                            statusSediaRacikan += "<br /><b class=\"text-info\"><i class=\"fa fa-exclamation-circle\"> Stok : " + number_format(parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</i></b>";
                            $("#btnSelesai").attr({
                                "disabled": "disabled"
                            }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                            console.log("Case A");
                            console.log(parseFloat(data.racikan[b].qty));
                            console.log(parseFloat(jlh_sedia));
                        } else {
                            var disabledStatus = $("#btnSelesai").attr('name');
                            if (typeof attr !== typeof undefined && attr !== false) {
                                $("#btnSelesai").attr({
                                    "disabled": "disabled"
                                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                                console.log("Case B");
                                console.log(parseFloat(data.racikan[b].qty));
                                console.log(parseFloat(jlh_sedia));
                            } else {
                                $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                                console.log("Case C");
                                console.log(parseFloat(data.racikan[b].qty));
                                console.log(parseFloat(jlh_sedia));
                            }
                        }*/

                        $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

                        $(newRacikanObat).attr({
                            "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid
                        }).addClass("obatSelector racikan-obat form-control").select2();
                        $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


                        $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                        $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");

                        var akumulasi = 0;

                        for(var batchSelKey in selectedBatchListRacikan) {
                            $(newCellRacikanObat).find("span ol").append("<li class=\"check_batch_avail " + ((selectedBatchListRacikanAmprah[batchSelKey].stok_terkini >= selectedBatchListRacikanAmprah[batchSelKey].jumlah) ? "text-success" : "text-danger") + "\" batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].batch + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired_date_parsed + " (" + selectedBatchListRacikanAmprah[batchSelKey].jumlah + ")</li>");
                        }
                        // for(var batchSelKey in selectedBatchListRacikan)
                        // {
                        //     if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                        //         if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                        //             $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
                        //             akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
                        //         }
                        //     }
                        // }


                        // if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {

                        //     for(var batchSelKey in selectedBatchListRacikanAmprah) {
                        //         if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                        //             if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                        //                 $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
                        //                 akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
                        //             }
                        //         }
                        //     }
                        // }


                        $(newCellRacikanObat).attr({
                            harga: harga_tertinggi_racikan
                        });

                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[0].jumlah + "<h5>");
                        } else {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
                        }

                        //$(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[b].jumlah + "<h5>");
                        $(newCellRacikanKeterangan).css({
                            "white-space": "pre-wrap"
                        }).html(data.racikan[b].change[0].keterangan);
                        $(newCellRacikanAlasan).html((data.racikan[b].change.length > 0) ? ((data.racikan[b].change[0].alasan_ubah !== undefined && data.racikan[b].change[0].alasan_ubah !== null && data.racikan[b].change[0].alasan_ubah !== "") ? data.racikan[b].change[0].alasan_ubah : "-") : "-");
                        //alert(b + " - " + racDetailKey);
                        if(racDetailKey === 0) {
                            $(newRacikanRow).append(newCellRacikanID);
                            $(newRacikanRow).append(newCellRacikanNama);
                            $(newRacikanRow).append(newCellRacikanSigna);
                            $(newRacikanRow).append(newCellRacikanJlh);

                            $(newRacikanRow).append(newCellRacikanObat);
                            $(newRacikanRow).append(newCellRacikanKeterangan);
                            $(newRacikanRow).append(newCellRacikanAlasan);
                        } else {
                            $(newRacikanRow).append(newCellRacikanObat);
                        }

                        $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
                        $(newCellRacikanAlasan).attr("rowspan", racikanDetail.length);
                        $("#load-detail-racikan tbody").append(newRacikanRow);

                    } else { //Butuh Amprah

                        var profit_racikan = 0;
                        var profit_type_racikan = "N";

                        for(var batchDetail in selectedBatchRacikan[0].profit)
                        {
                            if(selectedBatchRacikan[0].profit[batchDetail].penjamin === $("#nama-pasien").attr("set-penjamin"))
                            {
                                profit_racikan = parseFloat(selectedBatchRacikan[0].profit[batchDetail].profit);
                                profit_type_racikan = selectedBatchRacikan[0].profit[batchDetail].profit_type;
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
                        var newCellRacikanKeterangan = document.createElement("TD");
                        var newCellRacikanAlasan = document.createElement("TD");

                        $(newCellRacikanID).attr("rowspan", racikanDetail.length).html("<h5 class=\"autonum\">" + (b + 1) + "</h5>");
                        $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].change[0].signa_qty + " &times " + data.racikan[b].change[0].signa_pakai + "</h5>");
                        } else {
                            $(newCellRacikanSigna).addClass("text-center wrap_content").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
                        }

                        $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

                        var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                        var newRacikanObat = document.createElement("SELECT");
                        var statusSediaRacikan = "";
                        /*if(parseFloat(racikanDetail[racDetailKey].jumlah) <= parseFloat(racikanDetail[racDetailKey].sedia))
                        {
                            statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                        } else {
                            statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                        }*/

                        if(parseFloat(data.racikan[b].qty) <= parseFloat(jlh_sedia))
                        {
                            //statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        } else {
                            //statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        }

                        /*if((parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia)) > 0) {
                            statusSediaRacikan += "<br /><b class=\"text-info\"><i class=\"fa fa-exclamation-circle\"> Stok : " + number_format(parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</i></b>";
                            $("#btnSelesai").attr({
                                "disabled": "disabled"
                            }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                            console.log("Case A");
                            console.log(parseFloat(data.racikan[b].qty));
                            console.log(parseFloat(jlh_sedia));
                        } else {
                            var disabledStatus = $("#btnSelesai").attr('name');
                            if (typeof attr !== typeof undefined && attr !== false) {
                                $("#btnSelesai").attr({
                                    "disabled": "disabled"
                                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                                console.log("Case B");
                                console.log(parseFloat(data.racikan[b].qty));
                                console.log(parseFloat(jlh_sedia));
                            } else {
                                $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                                console.log("Case C");
                                console.log(parseFloat(data.racikan[b].qty));
                                console.log(parseFloat(jlh_sedia));
                            }
                        }*/

                        $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

                        $(newRacikanObat).attr({
                            "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
                            "group_racikan": data.racikan[b].uid
                        }).addClass("obatSelector racikan-obat form-control").select2();
                        $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


                        $(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                        $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");

                        var akumulasi = 0;
                        for(var batchSelKey in selectedBatchListRacikanAmprah) {
                            $(newCellRacikanObat).find("span ol").append("<li class=\"check_batch_avail " + ((selectedBatchListRacikanAmprah[batchSelKey].stok_terkini >= selectedBatchListRacikanAmprah[batchSelKey].jumlah) ? "text-success" : "text-danger") + "\" batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].batch + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired_date_parsed + " (" + selectedBatchListRacikanAmprah[batchSelKey].jumlah + ")</li>");
                        }
                        // for(var batchSelKey in selectedBatchListRacikanAmprah)
                        // {
                        //     if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                        //         if(parseFloat(selectedBatchListRacikanAmprah[batchSelKey].used) > 0) {
                        //             $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikanAmprah[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikanAmprah[batchSelKey].kode + "]</b> " + selectedBatchListRacikanAmprah[batchSelKey].expired + " (" + selectedBatchListRacikanAmprah[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikanAmprah[batchSelKey].gudang.nama + "]</b></li>");
                        //             akumulasi += parseFloat(selectedBatchListRacikanAmprah[batchSelKey].used);
                        //         }
                        //     }
                        // }


                        // if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {

                        //     for(var batchSelKey in selectedBatchListRacikanAmprah) {
                        //         if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                        //             if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                        //                 $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
                        //                 akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
                        //             }
                        //         }
                        //     }
                        // }


                        $(newCellRacikanObat).attr({
                            harga: harga_tertinggi_racikan
                        });

                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[0].jumlah + "<h5>");
                        } else {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
                        }

                        //$(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[b].jumlah + "<h5>");
                        $(newCellRacikanKeterangan).css({
                            "white-space": "pre-wrap"
                        }).html(data.racikan[b].change[0].keterangan);
                        $(newCellRacikanAlasan).html((data.racikan[b].change.length > 0) ? ((data.racikan[b].change[0].alasan_ubah !== undefined && data.racikan[b].change[0].alasan_ubah !== null && data.racikan[b].change[0].alasan_ubah !== "") ? data.racikan[b].change[0].alasan_ubah : "-") : "-");
                        //alert(b + " - " + racDetailKey);
                        if(racDetailKey === 0) {
                            $(newRacikanRow).append(newCellRacikanID);
                            $(newRacikanRow).append(newCellRacikanNama);
                            $(newRacikanRow).append(newCellRacikanSigna);
                            $(newRacikanRow).append(newCellRacikanJlh);

                            $(newRacikanRow).append(newCellRacikanObat);
                            $(newRacikanRow).append(newCellRacikanKeterangan);
                            $(newRacikanRow).append(newCellRacikanAlasan);
                        } else {
                            $(newRacikanRow).append(newCellRacikanObat);
                        }

                        $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
                        $(newCellRacikanAlasan).attr("rowspan", racikanDetail.length);
                        $("#load-detail-racikan tbody").append(newRacikanRow);


                    }
                }
            }
        }


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

        $("#btnPanggilResep").click(function() {
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
                            uid: targettedUID
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                tableResep.ajax.reload();
                                tableResep2.ajax.reload();
                                tableResep3.ajax.reload();
                                tableResepRiwayat.ajax.reload();
                                $("#modal-verifikasi").modal("hide");
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("#btnTerimaResep").click(function () {
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
                            uid: targettedUID
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                tableResep.ajax.reload();
                                tableResep2.ajax.reload();
                                tableResep3.ajax.reload();
                                tableResepRiwayat.ajax.reload();
                                $("#modal-verifikasi").modal("hide");
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
            targettedUID = uid;
            $("#btnTerimaResep").hide();
            $("#btnPanggilResep").show();

            //Load Resep Detail
            $.ajax({
                url:__HOSTAPI__ + "/Apotek/detail_resep_verifikator/" + uid,
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
                    $("#jk-pasien").html(targettedData.antrian.pasien_info.jenkel_nama);
                    $("#tanggal-lahir-pasien").html(targettedData.antrian.pasien_info.tanggal_lahir + " (" + targettedData.antrian.pasien_info.usia + " tahun)");
                    //$("#verifikator").html(targettedData.verifikator.nama);
                    var todayUnparsed = new Date("2022","01","03");
                    var checkparserDate = new Date(targettedData.created_at);
                    if(checkparserDate <= todayUnparsed) {
                        loadDetailResep(targettedData);    
                    } else {
                        loadDetailResep2(targettedData);
                    }
                    
                    //Modal Detail Resep Cuk
                    $("#modal-verifikasi").modal("show");

                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("body").on("click", ".btn-apotek-terima", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            targettedUID = uid;
            $("#btnTerimaResep").show();
            $("#btnPanggilResep").hide();

            //Load Resep Detail
            $.ajax({
                url:__HOSTAPI__ + "/Apotek/detail_resep_verifikator/" + uid,
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
                    $("#jk-pasien").html(targettedData.antrian.pasien_info.jenkel_nama);
                    $("#tanggal-lahir-pasien").html(targettedData.antrian.pasien_info.tanggal_lahir + " (" + targettedData.antrian.pasien_info.usia + " tahun)");
                    //$("#verifikator").html(targettedData.verifikator.nama);
                    var todayUnparsed = new Date("2022","01","03");
                    var checkparserDate = new Date(targettedData.created_at);
                    if(checkparserDate <= todayUnparsed) {
                        loadDetailResep(targettedData);    
                    } else {
                        loadDetailResep2(targettedData);
                    }
                    //Modal Detail Resep Cuk
                    $("#modal-verifikasi").modal("show");

                },
                error: function(response) {
                    console.log(response);
                }
            });


        });

        /*$("body").on("click", ".btn-verfikasi", function() {
            var id = $(this).attr("id").split("_");
            var dataRow = id[id.length - 1];
            var resepUID = id[id.length - 2];

            $("#modal-verifikasi").modal("show");
            targettedData = listResep[(dataRow - 1)];
            $("#nama-pasien").attr({
                "set-penjamin": targettedData.antrian.penjamin_data.uid
            }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name !== null)? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");

            loadDetailResep(targettedData);

            $(".obatSelector").attr({
                "disabled": "disabled"
            }).prop('disabled', true).select2({
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
                    }).addClass("obatSelector resep-obat form-control").prop('disabled', true).select2();
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
                    }).attr({
                        "disabled": "disabled"
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
                    }).attr({
                        "disabled": "disabled"
                    }).addClass("form-control qty_resep").attr({
                        "id": "qty_resep_" + a
                    }).val(parseFloat(data.detail[a].qty)).attr({
                        "disabled": "disabled"
                    });

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
                        }).addClass("obatSelector racikan-obat form-control").select2().prop('disabled', true);
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
                        }).attr({
                            "disabled": "disabled"
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
        }*/



        $("#btnProsesResep").click(function() {
            /*Swal.fire({
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
            });*/
        });

        /*function refreshBatch(item) {
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
        }*/
    });
</script>
<div id="modal-verifikasi" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Check Obat</h5>
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
                                <div class="alert alert-soft-info card-margin" role="alert">
                                    <h6>
                                        <i class="fa fa-paperclip"></i> Keterangan Resep
                                    </h6>
                                    <br />
                                    <div id="txt_keterangan_resep" style="color: #000 !important;"></div>
                                </div>
                                <table id="load-detail-resep" class="table table-bordered table-striped largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                        <th style="width: 40%;">Obat</th>
                                        <th class="wrap_content">Signa</th>
                                        <th class="wrap_content">Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Alasan Ubah</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="alert alert-soft-danger card-margin" role="alert">
                                    <h6>
                                        <i class="fa fa-paperclip"></i> Alasan Ubah
                                    </h6>
                                    <br />
                                    <div class="txt_alasan_ubah" style="color: #000 !important;"></div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="tab-racikan">
                                <div class="alert alert-soft-info card-margin" role="alert">
                                    <h6>
                                        <i class="fa fa-paperclip"></i> Keterangan Racikan
                                    </h6>
                                    <br />
                                    <div id="txt_keterangan_racikan" style="color: #000 !important;"></div>
                                </div>
                                <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                        <th style="width: 20%;">Racikan</th>
                                        <th class="wrap_content">Signa</th>
                                        <th class="wrap_content">Jumlah</th>
                                        <th style="width: 25%;">Obat</th>
                                        <th>Keterangan</th>
                                        <th>Alasan Ubah</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="alert alert-soft-danger card-margin" role="alert">
                                    <h6>
                                        <i class="fa fa-paperclip"></i> Alasan Ubah
                                    </h6>
                                    <br />
                                    <div class="txt_alasan_ubah" style="color: #000 !important;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnPanggilResep"><i class="fa fa-check"></i> Panggil</button>
                <button type="button" class="btn btn-success" id="btnTerimaResep"><i class="fa fa-check"></i> Sudah Terima</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>








<div id="modal-cetak" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Check Obat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg">
                    <div class="card">
                        <div class="card-header card-header-large bg-white d-flex align-items-center">
                            <h5 class="card-header__title flex m-0"><i class="fa fa-hashtag"></i> Detail Resep</h5>
                        </div>
                        <div class="card-header card-header-tabs-basic nav" role="tablist">
                            <a href="#cetak-utama" class="active" data-toggle="tab" role="tab" aria-controls="cetak-utama" aria-selected="true">Resep/Racikan</a>
                            <a href="#cetak-kajian" data-toggle="tab" role="tab" aria-selected="false">Kajian Apotek</a>
                        </div>
                        <div class="card-body tab-content" style="min-height: 100px;">
                            <div class="tab-pane active show fade" id="cetak-utama">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="cetak"></div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-purple pull-right" id="btnCetakResep"><i class="fa fa-print"></i> Cetak</button>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane show fade" id="cetak-kajian">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered largeDataType">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th colspan="2" style="width: 80%">Aspek Kajian</th>
                                                <th class="wrap_content">
                                                    Hasil
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td rowspan="3" class="wrap_content">a.</td>
                                                <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                    <b>Aspek Administrasi</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Resep Lengkap</td>
                                                <td id="hasil_kajian_resep_lengkap"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Pasien Sesuai</td>
                                                <td id="hasil_kajian_pasien_sesuai"></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="3" class="wrap_content">b.</td>
                                                <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                    <b>Aspek Farmasetik</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Benar Obat</td>
                                                <td id="hasil_kajian_benar_obat"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Benar Bentuk/Kekuatan/Jumlah</td>
                                                <td id="hasil_kajian_benar_bentuk"></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="6" class="wrap_content">c.</td>
                                                <td colspan="2" style="background: rgba(215, 242, 255 , .5) !important;">
                                                    <b>Aspek Klinik</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Benar Dosis/Frekuensi/Aturan Pakai</td>
                                                <td id="hasil_kajian_benar_dosis"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Benar Rute Pemberian</td>
                                                <td id="hasil_kajian_benar_rute"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Tidak Ada Interaksi Obat</td>
                                                <td id="hasil_kajian_interaksi"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Tidak Ada Duplikasi</td>
                                                <td id="hasil_kajian_duplikasi"></td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 30px">Tidak Alergi/Kontradiksi</td>
                                                <td id="hasil_kajian_alergi"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>