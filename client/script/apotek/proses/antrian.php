<script type="text/javascript">
    $(function () {
        var resepUID = __PAGES__[3];
        var targettedData = {};
        var allowProcess = false;
        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep_verifikator/" + resepUID,
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {

                targettedData = response.response_package.response_data[0];
                // $("#verifikator").html(targettedData.detail[0].verifikator.nama);
                $("#verifikator").html(targettedData.verifikator.nama);
                $("#txt_keterangan_resep").html(targettedData.keterangan);
                $("#txt_keterangan_racikan").html(targettedData.keterangan_racikan);
                $("#nama-pasien").attr({
                    "set-penjamin": targettedData.antrian.penjamin_data.uid
                }).html(((targettedData.antrian.pasien_info.panggilan_name !== undefined && targettedData.antrian.pasien_info.panggilan_name !== null) ? targettedData.antrian.pasien_info.panggilan_name.nama : "") + " " + targettedData.antrian.pasien_info.nama + "<b class=\"text-success\"> [" + targettedData.antrian.penjamin_data.nama + "]</b>");
                $("#jk-pasien").html(targettedData.antrian.pasien_info.jenkel_nama);
                $("#tanggal-lahir-pasien").html(targettedData.antrian.pasien_info.tanggal_lahir + " (" + targettedData.antrian.pasien_info.usia + " tahun)");
                //$("#verifikator").html(targettedData.verifikator.nama);
                console.clear();
                console.log(targettedData);
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
            console.clear();
            console.log(data);
            $("#txt_alasan_ubah").html((data.alasan_ubah !== undefined && data.alasan_ubah !== null && data.alasan_ubah !== "") ? data.alasan_ubah : "-");
            $("#load-detail-resep tbody tr").remove();
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
                        if(selectedBatchResep[bKey].gudang.uid === __UNIT__.gudang && parseFloat(selectedBatchResep[bKey].stok_terkini) > 0) {
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
                        $(newDetailCellID).addClass("text-center").html("<h5 class=\"autonum\">" + (a + 1) + "</h5>");

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
                        });

                        var newDetailCellQty = document.createElement("TD");
                        var newQty = document.createElement("INPUT");
                        var statusSedia = "";

                        /*if(parseFloat(data.detail[a].qty) <= parseFloat(data.detail[a].sedia))
                        {
                                statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + data.detail[a].sedia + "</b>";
                            } else {
                            statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + data.detail[a].sedia + "</b>";
                        }*/
                        if(parseFloat(data.detail[a].qty) <= parseFloat(jlh_sedia))
                        {
                            statusSedia = "<b class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        } else {
                            statusSedia = "<b class=\"text-danger\"><i class=\"fa fa-ban\"></i> Tersedia " + number_format(parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                        }

                        if((parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia)) > 0) {
                            statusSedia += "<br /><b class=\"text-warning\"><i class=\"fa fa-exclamation-circle\"></i>Butuh Amprah : " + number_format(parseFloat(data.detail[a].qty) - parseFloat(jlh_sedia), 2, ".", ",") + "</b>";
                            $("#btnSelesai").attr({
                                "disabled": "disabled"
                            }).removeClass("btn-success").addClass("btn-danger").html("<i class=\"fa fa-ban\"></i> Selesai");
                        } else {
                            var disabledStatus = $("#btnSelesai").attr('name');
                            if (typeof attr !== typeof undefined && attr !== false) {
                                // ...
                            } else {
                                $("#btnSelesai").removeAttr("disabled").removeClass("btn-danger").addClass("btn-success").html("<i class=\"fa fa-check\"></i> Selesai");
                            }
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
                var racikanDetail = data.racikan[b].detail;
                for(var racDetailKey = 0; racDetailKey < racikanDetail.length; racDetailKey++) {
                    var selectedBatchRacikan = refreshBatch(racikanDetail[racDetailKey].obat);
                    var selectedBatchListRacikan = [];
                    var selectedBatchListRacikanAmprah = [];
                    var harga_tertinggi_racikan = 0;
                    //var kebutuhan_racikan = parseFloat(data.racikan[b].qty);
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



                            if(selectedBatchRacikan[bKey].gudang.uid === __UNIT__.gudang) {
                                kebutuhan_racikan -= selectedBatchRacikan[bKey].stok_terkini;
                                jlh_sedia += selectedBatchRacikan[bKey].stok_terkini;
                                selectedBatchListRacikan.push(selectedBatchRacikan[bKey]);
                            } else {
                                butuh_amprah += selectedBatchRacikan[bKey].stok_terkini;
                                selectedBatchListRacikanAmprah.push(selectedBatchRacikan[bKey]);
                            }
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
                        for(var batchSelKey in selectedBatchListRacikan)
                        {
                            if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                                if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                                    $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
                                    akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
                                }
                            }
                        }


                        if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {

                            for(var batchSelKey in selectedBatchListRacikanAmprah) {
                                if(akumulasi < parseFloat(racikanDetail[racDetailKey].jumlah)) {
                                    if(parseFloat(selectedBatchListRacikan[batchSelKey].used) > 0) {
                                        $(newCellRacikanObat).find("span ol").append("<li batch=\"" + selectedBatchListRacikan[batchSelKey].batch + "\"><b>[" + selectedBatchListRacikan[batchSelKey].kode + "]</b> " + selectedBatchListRacikan[batchSelKey].expired + " (" + selectedBatchListRacikan[batchSelKey].used + ") <b class=\"text-info\">[" + selectedBatchListRacikan[batchSelKey].gudang.nama + "]</b></li>");
                                        akumulasi += parseFloat(selectedBatchListRacikan[batchSelKey].used);
                                    }
                                }
                            }
                        }


                        $(newCellRacikanObat).attr({
                            harga: harga_tertinggi_racikan
                        });

                        if(data.racikan[b].change.length > 0) {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[0].jumlah + "<h5>");
                        } else {
                            $(newCellRacikanJlh).html("<h5>" + data.racikan[b].qty + "<h5>");
                        }

                        //$(newCellRacikanJlh).html("<h5>" + data.racikan[b].change[b].jumlah + "<h5>");
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

            var antrian = targettedData.antrian;
            var asesmen = targettedData.asesmen;
            var departemen = antrian.departemen;
            var kunjungan = antrian.kunjungan;
            var dokter = antrian.dokter;
            var penjamin = antrian.penjamin;


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
                        data: {
                            request: "proses_resep",
                            resep: resepUID,
                            //antrian: antrian,
                            asesmen: asesmen,
                            kunjungan: kunjungan,
                            dokter: dokter,
                            penjamin: penjamin,
                            departemen: departemen
                        },
                        type:"POST",
                        success:function(response) {
                            console.clear();
                            console.log(response);
                            if(response.response_package.stok_result > 0) {
                                push_socket(__ME__, "resep_selesai_proses", "*", "Resep pasien a/n. " + $("#nama-pasien").html() + " selesai diproses!", "info").then(function() {
                                    Swal.fire(
                                        "Proses Berhasil!",
                                        "Resep berhasil diproses",
                                        "success"
                                    ).then((result) => {
                                        location.href = __HOSTNAME__ + "/apotek/proses";
                                    });
                                });
                            } else {
                                Swal.fire(
                                    "Proses Berhasil!",
                                    "Resep berhasil diproses",
                                    "success"
                                ).then((result) => {
                                    location.href = __HOSTNAME__ + "/apotek/proses";
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