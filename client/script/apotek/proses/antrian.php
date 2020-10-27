<script type="text/javascript">
    $(function () {
        var resepUID = __PAGES__[3];
        var targettedData = {};
        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep_lunas/" + resepUID,
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
                $("#verifikator").html(targettedData.verifikator.nama);
                loadDetailResep(targettedData);

            },
            error: function(response) {
                console.log(response);
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
                        if(selectedBatchResep[bKey].used > 0)
                        {
                            selectedBatchList.push(selectedBatchResep[bKey]);
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
                    if(data.detail[a].qty < data.detail[a].sedia)
                    {
                        statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + data.detail[a].sedia + "</b>";
                    } else {
                        statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia" + data.detail[a].sedia + "</b>";
                    }
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

                    if(selectedBatchRacikan.length > 0)
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

                        $(newCellRacikanID).attr("rowspan", racikanDetail.length).html(b + 1);
                        $(newCellRacikanNama).attr("rowspan", racikanDetail.length).html("<h5 style=\"margin-bottom: 20px;\">" + data.racikan[b].kode + "</h5>");
                        $(newCellRacikanSigna).addClass("text-center").attr("rowspan", racikanDetail.length).html("<h5>" + data.racikan[b].signa_qty + " &times " + data.racikan[b].signa_pakai + "</h5>");
                        $(newCellRacikanJlh).addClass("text-center").attr("rowspan", racikanDetail.length);

                        var RacikanObatData = load_product_resep(newRacikanObat, racikanDetail[racDetailKey].obat, false);
                        var newRacikanObat = document.createElement("SELECT");
                        $(newCellRacikanObat).append("<h5 class=\"text-info\">" + RacikanObatData.data[0].nama + " <b class=\"text-danger text-right\">[" + racikanDetail[racDetailKey].kekuatan + "]</b></h5>");

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

                        /*var totalObatRacikanRaw = parseFloat(harga_tertinggi_racikan);
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
                        });*/
                        $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
                        $(newCellRacikanKeterangan).html(data.racikan[b].keterangan);

                        if(racDetailKey < 1) {
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
                    }
                }
            }
        }

        $("select, input.form-control").attr({
            "disabled": "disabled"
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

        $("#btnSelesai").click(function () {
            Swal.fire({
                title: "Selesai Proses Resep?",
                text: "Pastikan batch sudah sesuai. Setelah konfirmasi stok akan terpotong",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Apotek",
                        async:false,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        data:{
                            request: "proses_resep",
                            resep: resepUID,
                            asesmen: targettedData.asesmen
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0)
                            {
                                Swal.fire(
                                    "Pembayaran Berhasil!",
                                    response.response_package.response_message,
                                    "success"
                                ).then((result) => {
                                    location.href = __HOST
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