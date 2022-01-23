<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        var selectedKunjungan = "", selectedPenjamin = "", selected_waktu_masuk = "", targettedDataResep = {};
        var kelompokObat = {};
        var nurse_station = __PAGES__[6];
        var uid_ranap = __PAGES__[7];
        var nurse_station_info = {};

        $.ajax({
            url: __HOSTAPI__ + "/Inap/detail_ns/" + __PAGES__[6],
            async: false,
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type: "GET",
            success: function (response) {
                nurse_station_info = response.response_package.response_data[0];
            },
            error: function (response) {
                //
            }
        });

        $.ajax({
            url: __HOSTAPI__ + "/Invoice/biaya_pasien_total/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                var data = [];
                if(response.response_package.response_data !== undefined && response.response_package.response_data !== null) {
                    data = response.response_package.response_data;
                }
                var filteredLunas = [], filteredTunggak = [];
                var totalLunas = 0, totalTunggak = 0;
                for(var a in data) {
                    for(var b in data[a].detail) {
                        if(data[a].detail[b].departemen === __POLI_IGD__) {
                            if(data[a].detail[b].status_bayar === "Y") {
                                filteredLunas.push({
                                    invoice: data[a].nomor_invoice,
                                    item: data[a].detail[b].item.nama,
                                    qty: data[a].detail[b].qty,
                                    harga: data[a].detail[b].harga,
                                    subtotal: data[a].detail[b].subtotal,
                                    keterangan: data[a].detail[b].keterangan
                                });
                                totalLunas += parseFloat(data[a].detail[b].subtotal);
                            } else {
                                filteredTunggak.push({
                                    invoice: data[a].nomor_invoice,
                                    item: data[a].detail[b].item.nama,
                                    qty: data[a].detail[b].qty,
                                    harga: data[a].detail[b].harga,
                                    subtotal: data[a].detail[b].subtotal,
                                    keterangan: data[a].detail[b].keterangan
                                });
                                totalTunggak += parseFloat(data[a].detail[b].subtotal);
                            }
                        }
                    }
                }

                var autonum = 1;
                for(var a in filteredLunas) {
                    var newRow = document.createElement("TR");
                    var newNo = document.createElement("TD");
                    var newItem = document.createElement("TD");
                    var newJlh = document.createElement("TD");
                    var newHarga = document.createElement("TD");
                    var newSub = document.createElement("TD");

                    $(newNo).html(autonum);
                    $(newItem).html("<span class=\"badge badge-info badge-custom-caption pull-right\">" + filteredLunas[a].invoice + "</span>" +
                        "<h6 style=\"padding-left: 20px;\">" + filteredLunas[a].item + " <br /><span class=\"text-success\"><i class=\"fa fa-check-circle\"></i> Lunas</span></h6><p>" + filteredLunas[a].keterangan + "</p>");
                    $(newJlh).html(filteredLunas[a].qty).addClass("number_style");
                    $(newHarga).html(number_format(filteredLunas[a].harga, 2, ".", ",")).addClass("number_style");
                    $(newSub).html(number_format(filteredLunas[a].subtotal, 2, ".", ",")).addClass("number_style");

                    $(newRow).append(newNo);
                    $(newRow).append(newItem);
                    $(newRow).append(newJlh);
                    $(newRow).append(newHarga);
                    $(newRow).append(newSub);

                    $("#biaya_pasien tbody").append(newRow);
                    autonum++;
                }

                for(var a in filteredTunggak) {
                    var newRow = document.createElement("TR");
                    var newNo = document.createElement("TD");
                    var newItem = document.createElement("TD");
                    var newJlh = document.createElement("TD");
                    var newHarga = document.createElement("TD");
                    var newSub = document.createElement("TD");

                    $(newNo).html(autonum);
                    $(newItem).html("<span class=\"badge badge-info badge-custom-caption pull-right\">" + filteredTunggak[a].invoice + "</span>" +
                        "<h6 style=\"padding-left: 20px;\">" + filteredTunggak[a].item + "<br /><span class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Tunggakan</span></h6><p>" + filteredTunggak[a].keterangan + "</p>");
                    $(newJlh).html(filteredTunggak[a].qty).addClass("number_style");
                    $(newHarga).html(number_format(filteredTunggak[a].harga, 2, ".", ",")).addClass("number_style");
                    $(newSub).html(number_format(filteredTunggak[a].subtotal, 2, ".", ",")).addClass("number_style");

                    $(newRow).append(newNo);
                    $(newRow).append(newItem);
                    $(newRow).append(newJlh);
                    $(newRow).append(newHarga);
                    $(newRow).append(newSub);

                    $("#biaya_pasien tbody").append(newRow);
                    autonum++;
                }

                $("#biaya_pasien tbody").append("<tr>" +
                    "<td colspan=\"4\" class=\"text-right\">Total Lunas</td>" +
                    "<td class=\"number_style text-success\">" + number_format(totalLunas, 2, ".", ",") + "</td>" +
                    "</tr>");

                $("#biaya_pasien tbody").append("<tr>" +
                    "<td colspan=\"4\" class=\"text-right\">Total Tunggakan</td>" +
                    "<td class=\"number_style text-danger\">" + number_format(totalTunggak, 2, ".", ",") + "</td>" +
                    "</tr>");
            },
            error: function(response) {
                console.log(response);
            }
        });

        var tableRiwayatObat = $("#table-riwayat-obat-inap").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/IGD",
                type: "POST",
                data: function(d) {
                    d.request = "riwayat_obat_igd";
                    d.pasien = __PAGES__[3];
                    d.kunjungan = __PAGES__[4];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response);
                    var returnedData = [];
                    if(response.response_package === undefined || response.response_package.response_data === undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    var filteredData = [];
                    for(var a in returnedData) {
                        if(returnedData[a].resep_pasien === __PAGES__[3]) {
                            filteredData.push(returnedData[a]);
                        }
                    }


                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = filteredData.length;
                    response.recordsFiltered = filteredData.length;

                    return filteredData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Resep"
            },
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.logged_at + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.resep_kode === null) ? "<h6 class=\"text-center\">-</h6>" : "<span class=\"badge badge-info badge-custom-caption\">" + row.resep_kode + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.resep_pasien_detail.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.obat + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style\">" + row.qty + "</h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.nama_petugas + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.keterangan;
                    }
                }
            ]
        });

        var tableResep = $("#table-resep-inap").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d) {
                    d.request = "resep_igd";
                    d.pasien = __PAGES__[3];
                    d.kunjungan = __PAGES__[4];
                    d.nurse_station = nurse_station;
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response.response_package === undefined || response.response_package.response_data === undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    var filteredData = [];
                    console.clear();


                    for(var a in returnedData) {
                        var currentResepQty = 0;
                        var ResepStokTersedia = 0;

                        var currentRacikanQty = 0;
                        var RacikanStokTerpakai = 0;

                        var habis = true;

                        var listMutasi = [];

                        for(var b in returnedData[a].detail) { //Resep
                            currentResepQty = parseFloat(returnedData[a].detail[b].qty);
                            for(var c in returnedData[a].detail[b].stok_ns) {
                                if(returnedData[a].detail[b].stok_ns[c].mutasi !== undefined && returnedData[a].detail[b].stok_ns[c].mutasi !== null && returnedData[a].detail[b].stok_ns[c].mutasi !== "") {
                                    listMutasi.push(returnedData[a].detail[b].stok_ns[c].mutasi);
                                }

                                if(returnedData[a].detail[b].stok_ns[c].status === "Y") {
                                    ResepStokTersedia += parseFloat(returnedData[a].detail[b].stok_ns[c].qty);
                                }
                            }
                        }

                        for(var x in returnedData[a].racikan) { //Racikan
                            currentRacikanQty = parseFloat(returnedData[a].racikan[x].qty);
                            for(var y in returnedData[a].racikan[x].ns_qty) {
                                RacikanStokTerpakai += parseFloat(returnedData[a].racikan[x].ns_qty[y].qty);
                            }
                        }

                        if(
                            returnedData[a].racikan.length > 0 &&
                            returnedData[a].detail.length > 0
                        ) {
                            habis = (((currentResepQty > ResepStokTersedia) && ResepStokTersedia === 0) && (currentRacikanQty === RacikanStokTerpakai));
                            habis = ((currentResepQty > ResepStokTersedia) && ResepStokTersedia === 0);
                        } else {
                            if(
                                returnedData[a].racikan.length > 0 &&
                                returnedData[a].detail.length === 0
                            ) {
                                habis = ((currentRacikanQty === RacikanStokTerpakai));
                            } else if (
                                returnedData[a].detail.length > 0 &&
                                returnedData[a].racikan.length === 0
                            ) {
                                habis = ((currentResepQty > ResepStokTersedia) && ResepStokTersedia === 0);
                            } else {
                                habis = false;
                            }
                        }

                        returnedData[a].habis = habis;
                        returnedData[a].mutasi_status = listMutasi;

                        filteredData.push(returnedData[a]);
                    }



                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return filteredData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Resep"
            },
            aaSorting: [[0, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.created_at_parsed + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.dokter_detail.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var detail = row.detail;
                        var parsedDetail = "<span class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Tidak ada resep</span>";
                        if(detail.length > 0) {
                            parsedDetail = "<div class=\"row\">";
                            for(var a in detail) {
                                if(detail[a].detail.nama !== "") {
                                    parsedDetail += "<div class=\"col-md-12\">" +
                                        "<span class=\"badge badge-info badge-custom-caption\"><i class=\"fa fa-tablets\"></i> " + detail[a].detail.nama + "</span><br />" +
                                        "<div style=\"padding-left: 20px;\">" + detail[a].signa_qty + " &times; " + detail[a].signa_pakai + " <label class=\"text-info\">[" + detail[a].qty + "]</label></div>" +
                                        "</div>";
                                }
                            }
                            parsedDetail += "</div>";
                        }

                        return parsedDetail;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var racikan = row.racikan;
                        var parsedDetail = "<span class=\"text-danger\"><i class=\"fa fa-times-circle\"></i> Tidak ada racikan</span>";
                        if(racikan.length > 0) {
                            parsedDetail = "<div class=\"row\">";
                            for(var a in racikan) {
                                var detailRacikan = [];
                                if(racikan[a].racikan_apotek.length > 0) {
                                    detailRacikan = racikan[a].racikan_apotek[a].detail;
                                    parsedDetail += "<div class=\"col-md-12\">" +
                                        "<span class=\"badge badge-outline-success badge-custom-caption\">" + racikan[a].kode + "</span><br />" +
                                        "<div style=\"padding-left: 20px;\">" + racikan[a].racikan_apotek[a].signa_qty + " &times; " + racikan[a].racikan_apotek[a].signa_pakai + " <label class=\"text-info\">[" + racikan[a].racikan_apotek[a].jumlah + "]</label></div>" +
                                        "<ul>";
                                    for(var b in detailRacikan) {
                                        parsedDetail += "<li style=\"margin-bottom: 5px;\"> " + detailRacikan[b].detail.nama + "</li>";
                                    }
                                    parsedDetail += "</ul></div>";
                                } else {
                                    detailRacikan = racikan[a].detail;
                                    parsedDetail += "<div class=\"col-md-12\">" +
                                        "<span class=\"badge badge-outline-success badge-custom-caption\">" + racikan[a].kode + "</span><br />" +
                                        "<div style=\"padding-left: 20px;\">" + racikan[a].signa_qty + " &times; " + racikan[a].signa_pakai + " <label class=\"text-info\">[" + racikan[a].qty + "]</label></div>" +
                                        "<ul>";
                                    for(var b in detailRacikan) {
                                        parsedDetail += "<li style=\"margin-bottom: 5px;\"> " + detailRacikan[b].detail.nama + "</li>";
                                    }
                                    parsedDetail += "</ul></div>";
                                }
                            }
                            parsedDetail += "</div>";
                        }
                        return parsedDetail;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.status_resep === "N") {
                            return "<span class=\"badge badge-warning badge-custom-caption\"><i class=\"fa fa-info-circle\"></i> Menunggu Verifikasi</span>";
                        } else if(row.status_resep === "K" || row.status_resep === "P") {
                            return "<span class=\"badge badge-info badge-custom-caption\"><i class=\"fa fa-info-circle\"></i> Belum Diserahkan</span>";
                        } else {
                            if(row.habis) {
                                var isMutasi = (row.mutasi_status.length > 0) ? "<br /><br /><b class=\"text-info\"><i class=\"fa fa-info-circle\"></i> Harap Terima Mutasi</b>" : "";
                                return "<span class=\"badge badge-danger badge-custom-caption\"><i class=\"fa fa-times-circle\"></i> Tidak Tersedia</span>" + isMutasi;
                            } else {
                                return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                    "<button class=\"btn btn-success btn-sm berikanObat\" id=\"resep_" + row.uid + "\">" +
                                    "<span><i class=\"fa fa-eye\"></i>Berikan Obat</span>" +
                                    "</button>" +
                                    "</div>";
                            }
                        }
                    }
                }
            ]
        });



        var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
            "ajax":{
                url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-medis/igd",
                type: "GET",
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var filteredData = [];
                    var data = response.response_package.response_data;

                    for(var a = 0; a < data.length; a++) {
                        if(
                            data[a].uid_pasien === __PAGES__[3] &&
                            data[a].uid_kunjungan === __PAGES__[4] &&
                            data[a].uid_poli === __POLI_IGD__
                        ) {
                            filteredData.push(data[a]);
                        }
                    }

                    if(filteredData.length > 0) {
                        selectedKunjungan = filteredData[0].uid_kunjungan;
                        selectedPenjamin = filteredData[0].uid_penjamin;
                        selected_waktu_masuk = filteredData[0].waktu_masuk;

                        $("#target_pasien").html(filteredData[0].pasien);
                        $("#rm_pasien").html(filteredData[0].no_rm);
                        $("#nama_pasien").html((filteredData[0].pasien_detail.panggilan_name === null) ? filteredData[0].pasien_detail.nama : filteredData[0].pasien_detail.panggilan_name.nama + " " +  filteredData[0].pasien_detail.nama);
                        $("#jenkel_pasien").html((filteredData[0].pasien_detail.jenkel_detail !== undefined && filteredData[0].pasien_detail.jenkel_detail !== null) ? filteredData[0].pasien_detail.jenkel_detail.nama : "");
                        $("#tempat_lahir_pasien").html(filteredData[0].pasien_detail.tempat_lahir);
                        $("#alamat_pasien").html(filteredData[0].pasien_detail.alamat);
                        $("#usia_pasien").html(filteredData[0].pasien_detail.usia);
                        $("#tanggal_lahir_pasien").html(filteredData[0].pasien_detail.tanggal_lahir_parsed);
                    } else {
                        //Pasien Detail
                        $.ajax({
                            url: __HOSTAPI__ + "/Pasien/pasien-detail/" + __PAGES__[3],
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"GET",
                            success:function(response) {
                                var pasienData = response.response_package.response_data;
                                $("#target_pasien").html(pasienData[0].nama);
                                $("#rm_pasien").html(pasienData[0].no_rm);
                                $("#nama_pasien").html((pasienData[0].panggilan_name === null) ? pasienData[0].nama : pasienData[0].panggilan_name.nama + " " +  pasienData[0].nama);
                                $("#usia_pasien").html(pasienData[0].usia);
                                $("#jenkel_pasien").html(pasienData[0].jenkel_detail.nama);
                                $("#tanggal_lahir_pasien").html(pasienData[0].tanggal_lahir_parsed);
                                $("#tempat_lahir_pasien").html(pasienData[0].tempat_lahir);
                                $("#alamat_pasien").html(pasienData[0].alamat);
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }

                    return filteredData;
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
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.waktu_masuk;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        /*return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + row.uid + "/" + row.uid_pasien + "/" + row.uid_kunjungan + "\" class=\"btn btn-success btn-sm\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail</span>" +
                            "</a>" +
                            "</div>";*/
                        return "";
                    }
                }
            ]
        });

        $("body").on("click", ".berikanObat", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $.ajax({
                url:__HOSTAPI__ + "/Apotek",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    request: "detail_resep_verifikator_post",
                    uid: id,
                    nurse_station: nurse_station
                },
                success:function(response) {
                    targettedDataResep = response.response_package.response_data[0];
                    $("#form-berikan-resep").modal("show");
                    $("#resep_dokter").html(targettedDataResep.dokter.nama);
                    $("#resep_tanggal").html(targettedDataResep.created_at_parsed);
                    $("#resep_verifikator").html(targettedDataResep.detail[0].verifikator.nama);

                    $("#resep-nama-pasien").attr({
                        "set-penjamin": targettedDataResep.antrian.penjamin_data.uid
                    }).html(((targettedDataResep.antrian.pasien_info.panggilan_name !== undefined && targettedDataResep.antrian.pasien_info.panggilan_name !== null) ? targettedDataResep.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedDataResep.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedDataResep.antrian.penjamin_data.nama + "]</b>");
                    $("#jk-pasien").html(targettedDataResep.antrian.pasien_info.jenkel_nama);
                    $("#tanggal-lahir-pasien").html(targettedDataResep.antrian.pasien_info.tanggal_lahir + " (" + targettedDataResep.antrian.pasien_info.usia + " tahun)");
                    loadDetailResep(targettedDataResep);

                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnSubmitBerikanObat").click(function () {
            var autonum = 1;
            $("#list-konfirmasi-berikan-obat tbody").html("");
            var allowSubmit = true;

            for(var a in targettedDataResep.detail) {
                var newResepConfRow = document.createElement("TR");
                var newResepNo = document.createElement("TD");
                var newResepItem = document.createElement("TD");
                var newResepQty = document.createElement("TD");
                var newResepQtyCount = document.createElement("INPUT");
                var newResepRemark = document.createElement("TEXTAREA");
                var sisaStok = 0;
                for(var ax in targettedDataResep.detail[a].stok_ns) {
                    sisaStok += parseFloat(targettedDataResep.detail[a].stok_ns[ax].qty);
                }


                $(newResepRemark).addClass("form-control").attr({
                    "placeholder": "Keterangan Tambahan"
                });

                var kebutuhan = parseFloat(targettedDataResep.detail[a].signa_pakai);

                $(newResepNo).html(autonum);

                var currentTotal = 0;

                //Check Ketersediaan Obat NS
                $.ajax({
                    url: __HOSTAPI__ + "/IGD/sedia_obat/" + targettedDataResep.uid + "/" + __PAGES__[3] + "/" + targettedDataResep.detail[a].detail.uid,
                    async: false,
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    type: "GET",
                    success: function (response) {
                        var batchList = response.response_package.response_data;
                        var totalItem = 0;
                        var saranBatch = [];
                        for(var bbA in batchList) {
                            if(parseFloat(batchList[bbA].qty) > 0 && batchList[bbA].resep === targettedDataResep.uid) {
                                totalItem += parseFloat(batchList[bbA].qty);
                                if(kebutuhan > 0) {
                                    saranBatch.push("<span class=\"badge badge-info badge-custom-caption\" qty=\"" + kebutuhan + "\" id=\"" + batchList[bbA].batch.uid + "\">" + batchList[bbA].batch.batch + " [" + batchList[bbA].batch.expired_date_parsed + "](" + kebutuhan + ")</span>");
                                    if(parseFloat(batchList[bbA].qty) > kebutuhan) {
                                        kebutuhan = 0;
                                    } else {
                                        kebutuhan -= parseFloat(batchList[bbA].qty);
                                    }
                                }
                            }
                        }

                        /*var batchListUsed = targettedDataResep.detail[a].batch;
                        var usedBatch = {};*/

                        /*for(var zbU in batchListUsed) {
                            if(batchListUsed[zbU].gudang.uid === nurse_station_info.gudang) {
                                if(usedBatch[batchListUsed[zbU].batch]) {
                                    usedBatch[batchListUsed[zbU].batch] = 0;
                                }
                                //
                                if(batchListUsed[zbU].stok_terkini > kebutuhan) {
                                    usedBatch[batchListUsed[zbU].batch] = kebutuhan;
                                    kebutuhan = 0;
                                } else if(batchListUsed[zbU].stok_terkini < kebutuhan) {
                                    //Tidak mencukupi
                                }
                            }
                        }*/

                        currentTotal = totalItem;

                        $(newResepQtyCount).val(parseFloat(targettedDataResep.detail[a].signa_pakai)).inputmask({
                            alias: 'decimal',
                            rightAlign: true,
                            placeholder: "0.00",
                            prefix: "",
                            autoGroup: false,
                            digitsOptional: true
                        }).css({
                            "max-width": "50px",
                            "float": "right"
                        }).attr({
                            "disabled": "disabled"
                        });



                        $(newResepItem).html("<span class=\"" + ((currentTotal < parseFloat(targettedDataResep.detail[a].signa_pakai)) ? "text-danger" : "") + "\" style=\"" + ((currentTotal < parseFloat(targettedDataResep.detail[a].signa_pakai)) ? "text-decoration: line-through" : "") + "\">" + targettedDataResep.detail[a].detail.nama + "</span><br />Sedia: " + totalItem + "<hr /><b>Saran Batch:</b><br />" + saranBatch.join(",") + "<hr />").attr({
                            "uid": targettedDataResep.detail[a].detail.uid
                        }).append(newResepRemark);
                    },
                    error: function (response) {
                        //
                    }
                });

                if(allowSubmit) {
                    if(currentTotal < parseFloat(targettedDataResep.detail[a].signa_pakai)) {
                        allowSubmit = false;
                    } else {
                        allowSubmit = true;
                    }
                }

                if(currentTotal < parseFloat(targettedDataResep.detail[a].signa_pakai)) {
                    $(newResepConfRow).addClass("habis");
                }


                $(newResepQty).append(newResepQtyCount);

                $(newResepConfRow).append(newResepNo);
                $(newResepConfRow).append(newResepItem);
                $(newResepConfRow).append(newResepQty);
                $(newResepConfRow).addClass("resep_item");
                $("#list-konfirmasi-berikan-obat tbody").append(newResepConfRow);
                autonum += 1;
            }

            for(var a in targettedDataResep.racikan) {
                var newResepConfRow = document.createElement("TR");
                var newResepNo = document.createElement("TD");
                var newResepItem = document.createElement("TD");
                var newResepQty = document.createElement("TD");
                var newResepQtyCount = document.createElement("INPUT");
                var newResepRemark = document.createElement("TEXTAREA");

                var racikanTerpakai = 0;
                for(var ay in targettedDataResep.racikan[a].ns_qty) {
                    racikanTerpakai += parseFloat(targettedDataResep.racikan[a].ns_qty[ay].qty);
                }

                $(newResepQtyCount).val(((racikanTerpakai === parseFloat(targettedDataResep.racikan[a].qty)) ? 0 : parseFloat(targettedDataResep.racikan[a].signa_pakai))).inputmask({
                    alias: 'decimal',
                    rightAlign: true,
                    placeholder: "0.00",
                    prefix: "",
                    autoGroup: false,
                    digitsOptional: true
                }).css({
                    "max-width": "50px",
                    "float": "right"
                }).attr({
                    "disabled": "disabled"
                });
                $(newResepRemark).addClass("form-control").attr({
                    "placeholder": "Keterangan Tambahan"
                });

                $(newResepNo).html(autonum);
                $(newResepItem).html("<span class=\"" + ((racikanTerpakai >= parseFloat(targettedDataResep.racikan[a].qty)) ? "text-danger" : "") + "\" style=\"" + ((racikanTerpakai >= parseFloat(targettedDataResep.racikan[a].qty)) ? "text-decoration: line-through" : "") + "\">" +targettedDataResep.racikan[a].kode + "</span>").attr({
                    "uid": targettedDataResep.racikan[a].kode
                }).append(newResepRemark);
                $(newResepQty).append(newResepQtyCount);

                $(newResepConfRow).append(newResepNo);
                $(newResepConfRow).append(newResepItem);
                $(newResepConfRow).append(newResepQty);
                if(racikanTerpakai >= parseFloat(targettedDataResep.racikan[a].qty)) {
                    $(newResepConfRow).addClass("habis");
                }
                $(newResepConfRow).addClass("racikan_item");
                $("#list-konfirmasi-berikan-obat tbody").append(newResepConfRow);
                autonum += 1;
            }

            if(!allowSubmit) {
                //$("#btnKonfirmasiBerikanObat").remove();
            }

            $("#form-konfirmasi-berikan-resep").modal("show");
        });

        $("#btnKonfirmasiBerikanObat").click(function () {
            var resep = targettedDataResep.uid;
            var item = [];

            $("#list-konfirmasi-berikan-obat tbody tr").each(function () {
                if(!$(this).hasClass("habis")) {
                    var row = $(this);
                    var obat = $(this).find("td:eq(1)").attr("uid");
                    var batch = {};
                    $(this).find("td:eq(1) .badge").each(function () {
                        var currentBatch = $(this).attr("id");
                        var currentBatchQty = $(this).attr("qty");
                        if(batch[currentBatch] === undefined) {
                            batch[currentBatch] = currentBatchQty;
                        }

                    });
                    var qty = parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));
                    var keterangan = $(this).find("td:eq(1) textarea").val();
                    if(obat !== "" && qty > 0) {
                        item.push({
                            resep: resep,
                            obat: obat,
                            qty: qty,
                            batch: batch,
                            keterangan: keterangan,
                            charge_stock: row.hasClass("resep_item")
                        });
                    }
                }
            });


            /*console.log({
                request: "tambah_riwayat_resep_igd",
                nurse_station: nurse_station,
                gudang: nurse_station_info.gudang,
                item: item
            });*/
            if(item.length > 0) {
                Swal.fire({
                    title: "Riwayat Pemberian Obat",
                    text: "Pastikan semua obat dan jumlah sudah sesuai dengan resep. Apakah data sudah benar?",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Tidak",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url:__HOSTAPI__ + "/IGD",
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"POST",
                            data: {
                                request: "tambah_riwayat_resep_igd",
                                nurse_station: nurse_station,
                                gudang: nurse_station_info.gudang,
                                item: item
                            },
                            success:function(response) {
                                $("#form-berikan-resep").modal("hide");
                                $("#form-konfirmasi-berikan-resep").modal("hide");
                                tableRiwayatObat.ajax.reload();
                                tableResep.ajax.reload();

                                /*var result = response.response_package.response_result;
                                if(result > 0) {
                                    $("#form-berikan-resep").modal("hide");
                                    $("#form-konfirmasi-berikan-resep").modal("hide");
                                }*/
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }
                });
            }
        });

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
            return {
                allow: (productData.length == selected.length),
                data: productData
            };
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


        function loadDetailResep(data) {
            $("#load-detail-resep tbody tr").remove();
            if(data.detail.length > 0) {
                for(var a = 0; a < data.detail.length; a++) {
                    if(data.detail[a].detail !== null) {
                        var ObatData = load_product_resep(newObat, data.detail[a].detail.uid, false);
                        var selectedBatchResep = refreshBatch(data.detail[a].detail.uid);
                        var selectedBatchList = [];

                        var harga_tertinggi = 0;
                        var kebutuhan = parseFloat(data.detail[a].qty);
                        var jlh_sedia = 0;
                        var butuh_amprah = 0;
                        for(bKey in selectedBatchResep)
                        {
                            if(selectedBatchResep[bKey].gudang.uid === nurse_station_info.gudang && parseFloat(selectedBatchResep[bKey].stok_terkini) > 0) {
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
                                    kebutuhan = kebutuhan - selectedBatchResep[bKey].stok_terkini;
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
                            $(newDetailCellSigna).html("<h5 class=\"text_center\">" + data.detail[a].signa_qty + " &times; " + data.detail[a].signa_pakai + "</h5>");

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
                            var statusSedia = "";

                            /*if(parseFloat(data.detail[a].qty) <= parseFloat(jlh_sedia))
                            {
                                statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia <br />" + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                            } else {
                                statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia <br />" + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                            }

                            if((parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia)) > 0) {
                                statusSedia += "<br /><b class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i>Butuh Amprah : " + number_format(parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                                $("#btnSubmitBerikanObat").attr({
                                    "disabled": "disabled"
                                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                            } else {
                                var disabledStatus = $("#btnSelesai").attr('name');
                                if (typeof attr !== typeof undefined && attr !== false) {
                                    // ...
                                } else {
                                    //$("#btnSubmitBerikanObat").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                                }
                            }*/

                            $(newDetailCellQty).addClass("text_center").append("<h5 class=\"text_center\">" + parseFloat(data.detail[a].qty) + "</h5>").append(statusSedia);
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
                            //=======================================
                            $(newDetailRow).append(newDetailCellID);
                            $(newDetailRow).append(newDetailCellObat);
                            $(newDetailRow).append(newDetailCellSigna);
                            $(newDetailRow).append(newDetailCellQty);
                            $(newDetailRow).append(newDetailCellKeterangan);

                            $("#load-detail-resep tbody").append(newDetailRow);
                        }
                    }
                }
            } else {
                $("#resep tbody").append("<tr><td colspan=\"5\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada resep</td></tr>");
            }









            //==================================================================================== RACIKAN
            $("#load-detail-racikan tbody").html("");
            if(data.racikan.length > 0) {
                for(var b = 0; b < data.racikan.length; b++) {
                    var racikanDetail = data.racikan[b].detail;
                    for(var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                        var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                        var selectedBatchListRacikan = [];
                        var harga_tertinggi_racikan = 0;
                        var kebutuhan_racikan = parseFloat(data.racikan[b].qty);
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

                            $(newCellRacikanID).attr("rowspan", racikanDetail.length).html((b + 1));
                            $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                            $(newCellRacikanSigna).addClass("text-center").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
                            $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

                            var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                            var newRacikanObat = document.createElement("SELECT");
                            var statusSediaRacikan = "";
                            /*if(parseFloat(data.racikan[b].qty) <= parseFloat(racikanDetail[racDetailKey].sedia))
                            {
                                statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                            } else {
                                statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + racikanDetail[racDetailKey].sedia + "</b>";
                            }*/

                            /*if(parseFloat(data.racikan[b].qty) <= parseFloat(jlh_sedia))
                            {
                                statusSediaRacikan = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia <br />" + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                            } else {
                                statusSediaRacikan = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia <br />" + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                            }*/

                            /*if((parseFloat(data.racikan[b].qty) - parseFloat(jlh_sedia)) > 0) {
                                statusSediaRacikan += "<br /><b class=\"text-info\"><i class=\"fa fa-exclamation-circle\"> Stok : " + number_format(parseFloat(data.racikan[b].qty) -parseFloat(jlh_sedia), 2, ".", ",") + "</i></b>";
                                $("#btnSelesai").attr({
                                    "disabled": "disabled"
                                }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                            } else {
                                var disabledStatus = $("#btnSelesai").attr('name');
                                if (typeof attr !== typeof undefined && attr !== false) {
                                    $("#btnSelesai").attr({
                                        "disabled": "disabled"
                                    }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                                } else {
                                    $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                                }
                            }*/

                            $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>").append(statusSediaRacikan);

                            $(newRacikanObat).attr({
                                "id": "racikan_obat_" + data.racikan[b].uid + "_" + racDetailKey,
                                "group_racikan": data.racikan[b].uid
                            }).addClass("obatSelector racikan-obat form-control").select2();
                            $(newRacikanObat).append("<option value=\"" + RacikanObatData.data[0].uid + "\">" + RacikanObatData.data[0].nama + "</option>").val(RacikanObatData.data[0].uid).trigger("change");


                            /*$(newCellRacikanObat).append("<b style=\"padding-top: 10px; display: block\">Batch Terpakai:</b>");
                            $(newCellRacikanObat).append("<span id=\"racikan_batch_" + data.racikan[b].uid + "_" + racDetailKey + "\" class=\"selected_batch\"><ol></ol></span>");
                            for(var batchSelKey in selectedBatchListRacikan)
                            {
                                $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ")</li>");
                            }*/

                            $(newCellRacikanObat).attr({
                                harga: harga_tertinggi_racikan
                            });

                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
                            $(newCellRacikanKeterangan).html(data.racikan[b].keterangan);
                            //alert(b + " - " + racDetailKey);
                            if(racDetailKey === 0) {
                                $(newRacikanRow).append(newCellRacikanID);
                                $(newRacikanRow).append(newCellRacikanNama);
                                $(newRacikanRow).append(newCellRacikanSigna);
                                $(newRacikanRow).append(newCellRacikanJlh);

                                $(newRacikanRow).append(newCellRacikanObat);
                                $(newRacikanRow).append(newCellRacikanKeterangan);
                            } else {
                                $(newRacikanRow).append(newCellRacikanObat);
                            }

                            $(newCellRacikanKeterangan).attr("rowspan", racikanDetail.length);
                            $("#load-detail-racikan tbody").append(newRacikanRow);
                        } else {
                            //console.log("No Batch");
                        }
                    }
                }
            } else {
                $("#load-detail-racikan tbody").append("<tr><td colspan=\"6\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada racikan</td></tr>");
            }
        }

        $("#btnTambahAsesmen").click(function() {
            $(this).attr({
                "disabled": "disabled"
            }).removeClass("btn-info").addClass("btn-warning").html("<i class=\"fa fa-sync\"></i> Menambahkan Asesmen");

            var formData = {
                request: "tambah_asesmen",
                penjamin: __PAGES__[5],
                kunjungan: __PAGES__[4],
                pasien: __PAGES__[3],
                poli: __POLI_INAP__
            };

            $.ajax({
                url: __HOSTAPI__ + "/Inap",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: formData,
                success:function(response) {
                    location.href = __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + response.response_package.response_values[0] + "/" + __PAGES__[3] + "/" + __PAGES__[4];
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btnPulangkanPasien").click(function () {
            Swal.fire({
                title: "Pemulangan Pasien",
                text: "Pasien sudah dapat pulang?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Get Riwayat Pemberian Obat
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/IGD",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "kalkulasi_sisa_obat_2",
                            kunjungan: selectedKunjungan,
                            pasien: __PAGES__[3],
                            gudang: nurse_station_info.gudang,
                            nurse_station: nurse_station
                        },
                        success: function (response) {
                            var data = [];
                            if(response.response_package !== undefined) {
                                data = response.response_package.response_data;
                            }

                            console.log(response);

                            var kebutuhan = 0;

                            for(var a in data) {
                                kebutuhan = parseFloat(data[a].resep);

                                if(kelompokObat[data[a].detail.uid] === undefined) {
                                    kelompokObat[data[a].detail.uid] = {
                                        detail: data[a].detail,
                                        batch: {}
                                    };
                                }

                                if(kelompokObat[data[a].detail.uid].batch[data[a].batch.uid] === undefined) {
                                    if(kebutuhan > 0 && parseFloat(data[a].qty) > 0) {
                                        if(parseFloat(data[a].berikan) > kebutuhan) {
                                            kelompokObat[data[a].detail.uid].batch[data[a].batch.uid] = {
                                                detail: data[a].batch,
                                                sisa: 0,
                                                aktual: 0,
                                                keterangan: ""
                                            };
                                            kebutuhan -= parseFloat(data[a].qty);


                                        } else {
                                            kelompokObat[data[a].detail.uid].batch[data[a].batch.uid] = {
                                                detail: data[a].batch,
                                                sisa: kebutuhan - parseFloat(data[a].berikan),
                                                aktual: kebutuhan - parseFloat(data[a].berikan),
                                                keterangan: ""
                                            };
                                            kebutuhan -= parseFloat(data[a].berikan);

                                        }
                                    }
                                }
                            }

                            $("#list-sisa-obat tbody").html("");

                            var autonum = 1;
                            for(var a in kelompokObat) {
                                var batchCounter = 1;
                                var batchLength = 0;
                                for(var b in kelompokObat[a].batch) {
                                    batchLength++;
                                }

                                for(var b in kelompokObat[a].batch) {
                                    var newrowBatch = document.createElement("TR");
                                    var newSisa = document.createElement("TD");
                                    var newAktual = document.createElement("TD");
                                    var newRemark = document.createElement("TD");
                                    var batchCaption = document.createElement("TD");

                                    $(newSisa).html(kelompokObat[a].batch[b].sisa).addClass("number_style");
                                    var actualInput = document.createElement("INPUT");
                                    var remark = document.createElement("INPUT");

                                    $(actualInput).inputmask({
                                        alias: 'decimal',
                                        rightAlign: true,
                                        placeholder: "0.00",
                                        prefix: "",
                                        autoGroup: false,
                                        digitsOptional: true
                                    }).addClass("form-control actual_input").val(parseFloat(kelompokObat[a].batch[b].sisa)).attr({
                                        "id": "actual_" + kelompokObat[a].batch[b].detail.uid,
                                        "produk": kelompokObat[a].detail.uid
                                    });

                                    $(remark).addClass("form-control remark_actual").attr({
                                        "placeholder": "Keterangan " + kelompokObat[a].batch[b].detail.batch,
                                        "id": "remark_" + kelompokObat[a].batch[b].detail.uid,
                                        "produk": kelompokObat[a].detail.uid
                                    });

                                    if(batchCounter === 1) {
                                        var newRow = document.createElement("TR");
                                        var newNo = document.createElement("TD");
                                        var newObat = document.createElement("TD");

                                        $(newNo).html(autonum).attr({
                                            "rowspan": batchLength
                                        });

                                        $(newObat).html(kelompokObat[a].detail.nama).attr({
                                            "rowspan": batchLength
                                        });

                                        $(newRow).append(newNo);
                                        $(newRow).append(newObat);
                                        $(newRow).append(batchCaption);
                                        $(newRow).append(newSisa);
                                        $(newRow).append(newAktual);
                                        $(newRow).append(newRemark);
                                        $("#list-sisa-obat tbody").append(newRow);

                                    } else {
                                        $(newrowBatch).append(batchCaption);
                                        $(newrowBatch).append(newSisa);
                                        $(newrowBatch).append(newAktual);
                                        $(newrowBatch).append(newRemark);
                                        $("#list-sisa-obat tbody").append(newrowBatch);
                                    }

                                    $(newAktual).append(actualInput);
                                    $(newRemark).append(remark);

                                    $(batchCaption).html(kelompokObat[a].batch[b].detail.batch);

                                    batchCounter++;
                                }

                                autonum++;
                            }

                            $("#form-kalkulasi-sisa-obat").modal("show");


                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });

        $("body").on("keyup", ".actual_input", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            var barang = $(this).attr("produk");
            kelompokObat[barang].batch[uid].aktual = parseFloat($(this).inputmask("unmaskedvalue"));
        });

        $("body").on("keyup", ".remark_actual", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            var barang = $(this).attr("produk");
            kelompokObat[barang].batch[uid].keterangan = $(this).val();
        });




        $("#btnKonfirmasiSisaObat").click(function () {

            Swal.fire({
                title: "Rawat Inap",
                text: "Selesaikan pelayanan rawat inap?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    var remarkAll = $("#remark_kembalikan_obat").val();
                    var parsedData = [];



                    for(var a in kelompokObat) {
                        for(var b in kelompokObat[a].batch) {
                            parsedData.push({
                                obat: kelompokObat[a].detail.uid,
                                batch: kelompokObat[a].batch[b].detail.uid,
                                sisa: kelompokObat[a].batch[b].sisa,
                                aktual: kelompokObat[a].batch[b].aktual,
                                keterangan: kelompokObat[a].batch[b].keterangan
                            });
                        }
                    }

                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/IGD",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "konfirmasi_retur_obat",
                            uid: uid_ranap,
                            status: "N",
                            gudang: nurse_station_info.gudang,
                            pasien: __PAGES__[3],
                            nama_pasien: $("#nama_pasien").html(),
                            item: parsedData,
                            remark: remarkAll,
                            kunjungan: __PAGES__[4],
                            jenis: $("input[name=\"txt_jenis_pulang\"]:checked").val(),
                            keterangan: $("#txt_keterangan_pulang").val()
                        },
                        success: function (response) {
                            console.clear();
                            console.log(response);
                            //location.href = __HOSTNAME__ + "/igdv2/perawat";
                        },
                        error: function (response) {

                        }
                    });
                }
            });
        });



        $(".print_manager").click(function() {
            var targetSurat = $(this).attr("id");
            $("#target-judul-cetak").html("CETAK " + targetSurat.toUpperCase() + " PASIEN");
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/pasien_" + targetSurat + ".php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    pc_customer: __PC_CUSTOMER__,
                    no_rm:$("#rm_pasien").html(),
                    pasien: "An. " + $("#nama_pasien").html(),
                    tanggal_lahir: $("#tanggal_lahir_pasien").html(),
                    usia: $("#usia_pasien").html() + " tahun",
                    dokter: __MY_NAME__,
                    waktu_masuk: selected_waktu_masuk,
                    alamat: $("#alamat_pasien").html(),
                    tempat_lahir: $("#tempat_lahir_pasien").html()
                },
                success: function (response) {
                    //$("#dokumen-viewer").html(response);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        pageTitle: "igd",
                        afterPrint: function() {
                            $("#cetak").modal("hide");
                            $("#dokumen-viewer").html("");
                        }
                    });
                }
            });
        });
    });
</script>



<div id="form-berikan-resep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Berikan Resep</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-group">
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-12">
                                        <b class="nama_pasien" id="resep-nama-pasien"></b>
                                        <br />
                                        <span class="jk_pasien" id="jk-pasien"></span>
                                        <br />
                                        <span class="tanggal_lahir_pasien" id="tanggal-lahir-pasien"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-body">
                                <div class="d-flex flex-row">
                                    <div class="col-md-12">
                                        <table class="form-mode table largeDataType">
                                            <tr>
                                                <td>
                                                    Diresep tanggal<br />
                                                    <b class="text-info" id="resep_tanggal"></b>
                                                </td>
                                                <td>
                                                    Dokter<br />
                                                    <b class="text-info" id="resep_dokter"></b>
                                                </td>
                                                <td>
                                                    Diverifikasi Oleh<br />
                                                    <b class="text-info" id="resep_verifikator"></b>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Resep</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade" id="resep-biasa">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="load-detail-resep" class="table table-bordered largeDataType">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                                    <th style="width: 40%;">Obat</th>
                                                    <th width="15%">Signa</th>
                                                    <th width="15%">Jumlah</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Racikan</h5>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade" id="resep-racikan">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table id="load-detail-racikan" class="table table-bordered largeDataType">
                                                <thead class="thead-dark">
                                                <tr>
                                                    <th class="wrap_content"><i class="fa fa-hashtag"></i></th>
                                                    <th width="20%;">Racikan</th>
                                                    <th style="width: 15%;">Signa</th>
                                                    <th class="wrap_content">Jumlah</th>
                                                    <th width="30%;">Obat</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-primary" id="btnSubmitBerikanObat">
                    <span>
                        <i class="fa fa-pills"></i> Berikan
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="form-konfirmasi-berikan-resep" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Konfirmasi Jumlah Obat</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered largeDataType" id="list-konfirmasi-berikan-obat">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Obat/Racikan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-success" id="btnKonfirmasiBerikanObat">
                    <span>
                        <i class="fa fa-check-circle"></i> Sudah Benar
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>



<div id="form-kalkulasi-sisa-obat" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Selesai Pelayanan Rawat Inap</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Informasi pasien pulang</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <h6>Jenis Pulang</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="P" checked/>
                                                <label class="form-check-label">
                                                    PAPS
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="D" />
                                                <label class="form-check-label">
                                                    Dokter
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="txt_keterangan_pulang">Keterangan Pulang:</label>
                                    <textarea placeholder="Keterangan pasien pulang" style="min-height: 100px;" class="form-control" id="txt_keterangan_pulang"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">Manajemen Farmasi</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered largeDataType" id="list-sisa-obat">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th class="wrap_content">No</th>
                                        <th>Obat/Racikan</th>
                                        <th class="wrap_content">Batch</th>
                                        <th class="wrap_content">Sisa</th>
                                        <th style="width: 200px;">Sisa Aktual</th>
                                        <th>Keterangan per Obat/Racikan</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <br />
                                <label for="remark_kembalikan_obat">Keterangan Mutasi:</label>
                                <textarea style="min-height: 150px;" class="form-control" id="remark_kembalikan_obat" placeholder="Keterangan pengembalian obat rawat inap..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-top: 20px;">
                        <span class="text-info">
                            <i class="fa fa-info-circle"></i> Informasi Pengembalian Obat Sisa Rawat Inap
                        </span>
                        <ol type="1">
                            <li>Obat racikan tidak dihitung dalam stok. Perlakuan sisa racikan akan ditanggung pasien</li>
                            <li>Pengembalian obat kepada apotek. Setelah data stok pengembalian obat diisi maka sistem akan melakukan <b class="uppercase text-danger">operasi mutasi dari nurse station kepada apotek</b>. Silahkan kembalikan obat sesuai jumlah yang diisi</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-success" id="btnKonfirmasiSisaObat">
                    <span>
                        <i class="fa fa-check-circle"></i> Proses Data
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>