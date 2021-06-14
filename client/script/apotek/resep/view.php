<script type="text/javascript">
    $(function () {
        var currentMetaData;
        $.ajax({
            url:__HOSTAPI__ + "/Apotek/detail_resep_2/" + __PAGES__[3],
            async:false,
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            type:"GET",
            success:function(response) {
                var data = response.response_package[0];
                if(data.resep !== undefined) {
                    currentMetaData = data.detail;
                    if(
                        currentMetaData.departemen === undefined ||
                        currentMetaData.departemen === null
                    ) {
                        currentMetaData.departemen = {
                            uid: __POLI_INAP__,
                            nama: "Rawat Inap"
                        };
                    }
                    $(".nama_pasien").html((currentMetaData.pasien.panggilan_name !== null) ? currentMetaData.pasien.panggilan_name.nama + " " + currentMetaData.pasien.nama : currentMetaData.pasien.nama);
                    $(".jk_pasien").html(currentMetaData.pasien.jenkel_detail.nama);
                    $(".tanggal_lahir_pasien").html(currentMetaData.pasien.tanggal_lahir_parsed);
                    $(".penjamin_pasien").html(currentMetaData.penjamin.nama);
                    $(".poliklinik").html(currentMetaData.departemen.nama);
                    $(".dokter").html(currentMetaData.dokter.nama);

                    if(data.resep.length > 0) {

                        var resep_obat_detail = data.resep;

                        keterangan_resep = data.resep[0].keterangan;
                        keterangan_racikan = data.resep[0].keterangan_racikan;

                        for(var resepKey in resep_obat_detail) {
                            autoResep({
                                "obat": resep_obat_detail[resepKey].obat,
                                "obat_detail": resep_obat_detail[resepKey].obat_detail,
                                "aturan_pakai": resep_obat_detail[resepKey].aturan_pakai,
                                "keterangan": resep_obat_detail[resepKey].keterangan,
                                "signaKonsumsi": resep_obat_detail[resepKey].signa_qty,
                                "signaTakar": resep_obat_detail[resepKey].signa_pakai,
                                "signaHari": resep_obat_detail[resepKey].qty,
                                //"pasien_penjamin_uid": pasien_penjamin_uid
                            });
                        }

                        //autoResep();
                    } else {
                        $("#table-resep tbody").append("<tr><td colspan=\"9\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada resep</td></tr>");
                    }

                    var racikan_detail = data.racikan;
                    if(racikan_detail.length === 0) {
                        $("#table-resep-racikan tbody.racikan").append("<tr><td colspan=\"8\" class=\"text-center text-info\"><i class=\"fa fa-info-circle\"></i> Tidak ada racikan</td></tr>");
                    } else {
                        for(var racikanKey in racikan_detail) {
                            autoRacikan({
                                uid: racikan_detail[racikanKey].uid,
                                nama: racikan_detail[racikanKey].kode,
                                keterangan: racikan_detail[racikanKey].keterangan,
                                signaKonsumsi: racikan_detail[racikanKey].signa_qty,
                                signaTakar: racikan_detail[racikanKey].signa_pakai,
                                signaHari: racikan_detail[racikanKey].qty,
                                item:racikan_detail[racikanKey].item,
                                aturan_pakai: racikan_detail[racikanKey].aturan_pakai
                            });
                            var itemKomposisi = racikan_detail[racikanKey].item;
                            for(var komposisiKey in itemKomposisi) {
                                var penjaminObatRacikanListUID = [];
                                var penjaminObatRacikanList = itemKomposisi[komposisiKey].obat_detail.penjamin;
                                for(var penjaminObatKey in penjaminObatRacikanList) {
                                    if(penjaminObatRacikanListUID.indexOf(penjaminObatRacikanList[penjaminObatKey].penjamin) < 0) {
                                        penjaminObatRacikanListUID.push(penjaminObatRacikanList[penjaminObatKey].penjamin);
                                    }
                                }

                                itemKomposisi[komposisiKey].satuan = "<b>" + itemKomposisi[komposisiKey].takar_bulat + "</b><sub nilaiExact=\"" + itemKomposisi[komposisiKey].ratio + "\">" + itemKomposisi[komposisiKey].takar_decimal + "</sub>";

                                autoKomposisi((parseInt(racikanKey) + 1), itemKomposisi[komposisiKey], racikan_detail[racikanKey].qty);
                            }
                        }
                    }


                    if(racikan_detail.length > 0) {
                        //autoRacikan();
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });








        function autoResep(setter = {
            "obat": "",
            "obat_detail": {},
            "aturan_pakai": 0,
            "keterangan": "",
            "signaKonsumsi": 0,
            "signaTakar": 0,
            "signaHari": 0,
            "pasien_penjamin_uid": ""
        }) {
            $("#table-resep tbody tr").removeClass("last-resep");
            var newRowResep = document.createElement("TR");
            $(newRowResep).addClass("last-resep");
            var newCellResepID = document.createElement("TD");
            var newCellResepObat = document.createElement("TD");
            var newCellResepJlh = document.createElement("TD");
            var newCellResepSatuan = document.createElement("TD");
            var newCellResepSigna1 = document.createElement("TD");
            var newCellResepSigna2 = document.createElement("TD");
            var newCellResepSigna3 = document.createElement("TD");
            var newCellHarga = document.createElement("TD");
            var newCellResepPenjamin = document.createElement("TD");
            var newCellResepAksi = document.createElement("TD");

            var newObat = document.createElement("SELECT");
            $(newCellResepObat).append(newObat).append("<ol></ol>");

            $(newCellResepObat).append(
                "<div class=\"row\" style=\"padding-top: 5px;\">" +
                "<div style=\"position: relative\" class=\"col-md-12 penjamin-container text-right\"></div>" +
                "<div class=\"col-md-7 aturan-pakai-container\"><span>Aturan Pakai</span></div>" +
                "<div class=\"col-md-5 kategori-obat-container\"><span>Kategori Obat</span><br /></div>" +
                "<div style=\"position: relative; padding-top: 5px;\" class=\"col-md-12 keterangan-container\"></div>" +
                "</div>");
            var newAturanPakai = document.createElement("SELECT");
            var dataAturanPakai = autoAturanPakai();

            $(newCellResepObat).find("div.aturan-pakai-container").append(newAturanPakai);
            $(newAturanPakai).addClass("form-control aturan-pakai");
            $(newAturanPakai).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for(var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakai).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }

            var keteranganPerObat = document.createElement("TEXTAREA");
            $(newCellResepObat).find("div.keterangan-container").append("<span>Keterangan</span>").append(keteranganPerObat);
            $(keteranganPerObat).addClass("form-control").attr({
                "placeholder": "Keterangan per Obat"
            }).css({
                "min-height": "200px"
            }).val(setter.keterangan);

            var itemData = [];
            var parsedItemData = [];
            var obatNavigator = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }

                obatNavigator.push(itemData[dataKey].uid);
                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil !== null) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            var harga_tertinggi = 0;

            $(newObat).addClass("form-control resep-obat").select2({
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
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
                placeholder: "Pilih Obat",
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
            }).on("select2:select", function(e) {
                var data = e.params.data;
                var id = $(this).attr("id").split("_");
                id = id[id.length - 1];

                $(this).children("[value=\""+ data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });


                var dataKategoriPerObat = autoKategoriObat(data["id"]);
                var kategoriObatDOM = "";
                if(dataKategoriPerObat.length > 0) {
                    $(newCellResepObat).find("div.kategori-obat-container").html("");
                    for(var kategoriObatKey in dataKategoriPerObat) {
                        if(
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                    $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                }

                refreshBatch(data.id, id);

                $(newCellResepSatuan).html(data["satuan-caption"]);
            });




            if(setter.obat !== "") {
                $(newObat).append("<option title=\"" + setter.obat_detail.nama + "\" value=\"" + setter.obat + "\" penjamin-list=\"" + setter.obat_detail.penjamin.join(",") + "\">" + setter.obat_detail.nama + "</option>");
                $(newObat).select2("data", {id: setter.obat, text: setter.obat_detail.nama});
                $(newObat).trigger("change");

                if($(newObat).val() != "none") {
                    var dataKategoriPerObat = autoKategoriObat(setter.obat);
                    var kategoriObatDOM = "";
                    if(dataKategoriPerObat.length > 0) {
                        for(var kategoriObatKey in dataKategoriPerObat) {
                            if(
                                dataKategoriPerObat[kategoriObatKey].kategori !== undefined&&
                                dataKategoriPerObat[kategoriObatKey].kategori !== null
                            ) {
                                kategoriObatDOM += "<span class=\"badge badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                            }
                        }
                        $(newCellResepObat).find("div.kategori-obat-container").append(kategoriObatDOM);
                    }
                }
            }



            var newJumlah = document.createElement("INPUT");
            $(newCellResepJlh).append(newJumlah);
            $(newJumlah).addClass("form-control resep_jlh_hari").inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).attr({
                "placeholder": "0"
            }).val((setter.signaHari == 0) ? "" : setter.signaHari);

            var newKonsumsi = document.createElement("INPUT");
            $(newCellResepSigna1).append(newKonsumsi);
            $(newKonsumsi).addClass("form-control resep_konsumsi").attr({
                "placeholder": "0"
            }).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).val((setter.signaKonsumsi == 0) ? "" : setter.signaKonsumsi);

            $(newCellResepSigna2).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newTakar = document.createElement("INPUT");
            $(newCellResepSigna3).append(newTakar);
            $(newTakar).addClass("form-control resep_takar").attr({
                "placeholder": "0"
            }).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).val((setter.signaTakar == 0) ? "" : setter.signaTakar);


            var newDeleteResep = document.createElement("BUTTON");
            //$(newCellResepAksi).append(newDeleteResep);
            $(newDeleteResep).addClass("btn btn-sm btn-danger resep_delete").html("<i class=\"fa fa-ban\"></i>");

            $(newCellHarga).addClass("number_style").html(harga_tertinggi);

            $(newRowResep).append(newCellResepID);
            $(newRowResep).append(newCellResepObat);
            $(newRowResep).append(newCellResepSigna1);
            $(newRowResep).append(newCellResepSigna2);
            $(newRowResep).append(newCellResepSigna3);
            $(newRowResep).append(newCellResepJlh);
            $(newRowResep).append(newCellResepSatuan);
            $(newRowResep).append(newCellHarga);
            $(newRowResep).append(newCellResepAksi);
            $("#table-resep").append(newRowResep);

            rebaseResep();
        }

        function rebaseResep() {
            $("#table-resep tbody tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "resep_row_" + id
                });
                $(this).find("td:eq(0)").html(id);
                $(this).find("td:eq(1) select.resep-obat").attr({
                    "id": "resep_obat_" + id
                });

                refreshBatch($(this).find("td:eq(1) select.resep-obat").val(), id);

                $(this).find("td:eq(1) ol").attr({
                    "id": "batch_obat_" + id
                });

                //load_product_resep($(this).find("td:eq(1) select.resep-obat"), "");
                if($(this).find("td:eq(1) select.resep-obat").val() != "none") {
                    /*var penjaminAvailable = $(this).find("td:eq(1) select option:selected").attr("penjamin-list").split(",");
                    //checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);*/
                }

                $(this).find("td:eq(2) input:eq(0)").attr({
                    "id": "resep_signa_konsumsi_" + id
                });
                $(this).find("td:eq(4) input:eq(0)").attr({
                    "id": "resep_signa_takar_" + id
                });
                $(this).find("td:eq(5) input").attr({
                    "id": "resep_jlh_hari_" + id
                });
                $(this).find("td:eq(6)").attr({
                    "id": "resep_satuan_" + id
                });
                $(this).find("td:eq(7)").attr({
                    "id": "harga_obat_" + id
                });
                $(this).find("td:eq(8) button").attr({
                    "id": "resep_delete_" + id
                });
            });
        }

        function autoAturanPakai() {
            var dataAturanPakai;
            $.ajax({
                url:__HOSTAPI__ + "/Terminologi/terminologi-items/15",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    dataAturanPakai = response.response_package.response_data;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return dataAturanPakai;

        }

        function autoKategoriObat(obat) {
            var kategoriObat;
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/kategori_per_obat/" + obat,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    kategoriObat = response.response_package;
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return kategoriObat;
        }

        function refreshBatch(item, rowTarget = "", type = "resep") {
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

                    /*var dates = [];
                    for(var a in response.response_package) {
                        dates.push(response.response_package[a].expired_sort);
                    }
                    console.log(dates);*/

                    if(batchData !== null) {
                        if(rowTarget !== "") {

                            var selectedBatchList = [];
                            var uniqueBatch = [];
                            var harga_tertinggi = 0;
                            var total_kebutuhan = 0;
                            var kebutuhan = 0;



                            if(type === "resep") {

                                total_kebutuhan = $("#resep_jlh_hari_" + rowTarget).inputmask("unmaskedvalue");
                                kebutuhan = $("#resep_jlh_hari_" + rowTarget).inputmask("unmaskedvalue");

                                for(bKey in batchData) {

                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang) {

                                        if(batchData[bKey].harga > harga_tertinggi) {
                                            harga_tertinggi = batchData[bKey].harga;
                                        }

                                        if(kebutuhan > 0 && batchData[bKey].stok_terkini > 0) {

                                            if(kebutuhan > batchData[bKey].stok_terkini) {
                                                console.log("Ada " + parseFloat(batchData[bKey].stok_terkini) + " di " + batchData[bKey].gudang.uid);
                                                console.log(kebutuhan);

                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                                kebutuhan -= parseFloat(batchData[bKey].stok_terkini);
                                                if(uniqueBatch.indexOf(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid) < 0) {
                                                    selectedBatchList.push(batchData[bKey]);
                                                    uniqueBatch.push(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid);
                                                }
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                                kebutuhan = 0;
                                                if(uniqueBatch.indexOf(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid) < 0) {
                                                    selectedBatchList.push(batchData[bKey]);
                                                    uniqueBatch.push(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid);
                                                }
                                            }


                                        }
                                    } else {
                                        console.log("Ada " + parseFloat(batchData[bKey].stok_terkini) + " di " + batchData[bKey].gudang.uid);
                                    }
                                }



                                if(selectedBatchList.length > 0) {
                                    var profitList = selectedBatchList[0].profit
                                    for(var profKey in profitList) {
                                        if (profitList[profKey].penjamin === currentMetaData.penjamin.uid) {
                                            selectedProfitType = profitList[profKey].profit_type;
                                            selectedProfitValue = parseFloat(profitList[profKey].profit);
                                        }
                                    }

                                    var finalTotal = 0;
                                    var rawTotal = harga_tertinggi;

                                    if(selectedProfitType === "N") {
                                        finalTotal = rawTotal;
                                    } else if(selectedProfitType === "P") {
                                        finalTotal = rawTotal + (selectedProfitValue / 100 * rawTotal);
                                    } else {
                                        finalTotal = rawTotal + selectedProfitValue;
                                    }

                                    $("#batch_obat_" + rowTarget + " li").remove();
                                    for(var batchSelKey in selectedBatchList) {
                                        $("#batch_obat_" + rowTarget).append("<li class=\"" + ((selectedBatchList[batchSelKey].used < total_kebutuhan) ? "text-danger" : "text-success") + "\" batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ") - " + selectedBatchList[batchSelKey].gudang.nama + ((selectedBatchList[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                    }

                                    $("#batch_obat_" + rowTarget).attr("harga", finalTotal);

                                    //Calculate harga
                                    $("#harga_obat_" + rowTarget).html(number_format(finalTotal * total_kebutuhan, 2, ".", ","));
                                }
                            } else {

                                //racikan_jumlah_1
                                var groupExplitor = rowTarget.split("_");

                                total_kebutuhan = $("#jlh_komposisi_" + groupExplitor[0] + "_" + groupExplitor[1]).inputmask("unmaskedvalue");
                                kebutuhan = total_kebutuhan;

                                if(kebutuhan <= 0) {
                                    $("#jlh_komposisi_" + groupExplitor[0] + "_" + groupExplitor[1]).css({
                                        "background": "red"
                                    });
                                }


                                for(bKey in batchData)
                                {
                                    if(batchData[bKey].gudang.uid === __UNIT__.gudang) {
                                        if(batchData[bKey].harga > harga_tertinggi)
                                        {
                                            harga_tertinggi = batchData[bKey].harga;
                                        }

                                        if(kebutuhan > 0 && batchData[bKey].stok_terkini > 0)
                                        {
                                            if(kebutuhan > batchData[bKey].stok_terkini)
                                            {
                                                batchData[bKey].used = parseFloat(batchData[bKey].stok_terkini);
                                            } else {
                                                batchData[bKey].used = parseFloat(kebutuhan);
                                            }
                                            kebutuhan = kebutuhan - batchData[bKey].stok_terkini;
                                            if(uniqueBatch.indexOf(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid) < 0) {
                                                selectedBatchList.push(batchData[bKey]);
                                                uniqueBatch.push(batchData[bKey].batch + "-" + batchData[bKey].gudang.uid);
                                            }

                                        }
                                    }
                                }


                                //Profit Manager
                                var selectedProfitType = "N";
                                var selectedProfitValue = 0;



                                if(selectedBatchList.length > 0) {
                                    var profitList = selectedBatchList[0].profit
                                    for(var profKey in profitList) {
                                        if (profitList[profKey].penjamin === currentMetaData.penjamin.uid) {
                                            selectedProfitType = profitList[profKey].profit_type;
                                            selectedProfitValue = parseFloat(profitList[profKey].profit);
                                        }
                                    }

                                    var finalTotal = 0;
                                    var rawTotal = parseFloat(harga_tertinggi);

                                    if(selectedProfitType === "N") {
                                        finalTotal = rawTotal;
                                    } else if(selectedProfitType === "P") {
                                        finalTotal = rawTotal + (selectedProfitValue / 100 * rawTotal);
                                    } else {
                                        finalTotal = rawTotal + selectedProfitValue;
                                    }


                                    //Racikan session
                                    $("#obat_komposisi_batch_" + rowTarget).attr({
                                        "harga": finalTotal
                                    });

                                    $("#obat_komposisi_batch_" + rowTarget + " li").remove();
                                    for(var batchSelKey in selectedBatchList) {
                                        if(selectedBatchList[batchSelKey].used > 0) {
                                            $("#obat_komposisi_batch_" + rowTarget).append("<li class=\"" + ((selectedBatchList[batchSelKey].used < total_kebutuhan) ? "text-danger" : "text-success") + "\" batch=\"" + selectedBatchList[batchSelKey].batch + "\"><b>[" + selectedBatchList[batchSelKey].kode + "]</b> " + selectedBatchList[batchSelKey].expired + " (" + selectedBatchList[batchSelKey].used + ") - " + selectedBatchList[batchSelKey].gudang.nama + ((selectedBatchList[batchSelKey].used < total_kebutuhan) ? " <i class=\"fa fa-exclamation-triangle text-danger\"></i> Butuh Amprah" : " <i class=\"fa fa-check-circle text-success\"></i>") + "</li>");
                                        }
                                    }

                                    var totalKalkulasi = 0;
                                    //Kalkulasi total harga komposisi
                                    $("#komposisi_" + groupExplitor[0] + " tbody tr").each(function() {
                                        var attrHarga = $(this).find("td:eq(1) ol").attr("harga");
                                        if (typeof attrHarga !== typeof undefined && attrHarga !== false) {
                                            totalKalkulasi += parseFloat($(this).find("td:eq(1) ol").attr("harga")) * parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue"));
                                        }
                                    });


                                    $("#racikan_harga_" + groupExplitor[0]).html(number_format(totalKalkulasi, 2, ".", ","));
                                }
                            }
                        }
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
            return batchData;
        }

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            refreshBatch($("#resep_obat_" + id).val(), id);
        });

        /*$("body").on("keyup", ".racikan_signa_jlh", function () {
            var groupRacikan = $(this).attr("id").split("_");
            groupRacikan = groupRacikan[groupRacikan.length - 1];
            $("#komposisi_" + groupRacikan + " tbody tr").each(function(e) {
                refreshBatch($(this).find("td:eq(1) h6").attr("uid-obat"), groupRacikan + "_" + (e + 1), "racikan");
            });
        });*/







        function autoRacikan(setter = {
            "uid": "",
            "nama": "",
            "keterangan": "",
            "signaKonsumsi": "",
            "signaTakar": "",
            "signaHari": "",
            "aturan_pakai": "",
            "item":[]
        }) {
            $("#table-resep-racikan tbody.racikan tr").removeClass("last-racikan");
            var newRacikanRow = document.createElement("TR");
            $(newRacikanRow).attr("uid", setter.uid);
            $(newRacikanRow).addClass("last-racikan racikan-master");

            var newRacikanCellID = document.createElement("TD");
            var newRacikanCellNama = document.createElement("TD");
            var newRacikanCellSignaA = document.createElement("TD");
            var newRacikanCellSignaX = document.createElement("TD");
            var newRacikanCellSignaB = document.createElement("TD");
            var newRacikanCellJlh = document.createElement("TD");
            var newRacikanCellHarga = document.createElement("TD");
            var newRacikanCellAksi = document.createElement("TD");

            $(newRacikanCellHarga).addClass("number_style master-racikan-cell").append("<span></span>");

            $(newRacikanCellID).addClass("master-racikan-cell");
            $(newRacikanCellNama).addClass("master-racikan-cell");
            $(newRacikanCellSignaA).addClass("master-racikan-cell");
            $(newRacikanCellSignaX).addClass("master-racikan-cell");
            $(newRacikanCellSignaB).addClass("master-racikan-cell");
            $(newRacikanCellJlh).addClass("master-racikan-cell");
            $(newRacikanCellAksi).addClass("master-racikan-cell");

            var newRacikanNama = document.createElement("INPUT");
            $(newRacikanCellNama).append(newRacikanNama);
            $(newRacikanNama).addClass("form-control racikan_nama").css({
                "margin-bottom": "20px"
            }).attr({
                "placeholder": "Nama Racikan"
            }).val(setter.nama);

            $(newRacikanCellNama).append(
                "<h6 style=\"padding-bottom: 10px;\">" +
                "Komposisi:" +
                "<button style=\"margin-left: 20px;\" class=\"btn btn-sm btn-info tambahKomposisi\"" +
                "<i class=\"fa fa-plus\"></i> Tambah" +
                "</button>" +
                "</h6>" +
                "<table class=\"table table-bordered komposisi-racikan largeDataType\" style=\"margin-top: 10px;\">" +
                "<thead class=\"thead-dark\">" +
                "<tr>" +
                "<th class=\"wrap_content\">No</th>" +
                "<th>Obat</th>" +
                /*"<th class=\"\">@</th>" +*/
                "<th class=\"wrap_content\">Jlh Terpakai</th>" +
                "<th>Kekuatan</th>" +
                "<th class=\"wrap_content\">Aksi</th>" +
                "<tr>" +
                "</thead>" +
                "<tbody class=\"komposisi-item\"></tbody>" +
                "</table>"
            );

            var newAturanPakaiRacikan = document.createElement("SELECT");

            var dataAturanPakai = autoAturanPakai();

            $(newAturanPakaiRacikan).addClass("form-control aturan-pakai");
            var newKeteranganRacikan = document.createElement("TEXTAREA");
            $(newRacikanCellNama).append("<span>Aturan Pakai</span>").append(newAturanPakaiRacikan).append("<span>Keterangan</span>").append(newKeteranganRacikan);
            $(newAturanPakaiRacikan).append("<option value=\"none\">Pilih Aturan Pakai</option>").select2();
            for(var aturanPakaiKey in dataAturanPakai) {
                $(newAturanPakaiRacikan).append("<option " + ((dataAturanPakai[aturanPakaiKey].id == setter.aturan_pakai) ? "selected=\"selected\"" : "") + " value=\"" + dataAturanPakai[aturanPakaiKey].id + "\">" + dataAturanPakai[aturanPakaiKey].nama + "</option>")
            }
            $(newKeteranganRacikan).addClass("form-control").attr({
                "placeholder": "Keterangan racikan"
            }).css({
                "min-height": "200px"
            }).val(setter.keterangan);

            /*var newRacikanObat = document.createElement("SELECT");
            var newObatTakar = document.createElement("INPUT");
            $(newRacikanCellObat).append(newRacikanObat);
            var addAnother = load_product_resep(newRacikanObat, "");
            $(newRacikanCellObat).append("<br /><b>Takaran</b>");
            $(newRacikanCellObat).append(newObatTakar);
            $(newRacikanObat).addClass("form-control").select2();
            $(newObatTakar).addClass("form-control");*/

            var newRacikanSignaA = document.createElement("INPUT");
            $(newRacikanCellSignaA).append(newRacikanSignaA);
            $(newRacikanSignaA).addClass("form-control racikan_signa_a").attr({
                "placeholder": "0"
            }).val(setter.signaKonsumsi).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            $(newRacikanCellSignaX).html("<i class=\"fa fa-times signa-sign\"></i>");

            var newRacikanSignaB = document.createElement("INPUT");
            $(newRacikanCellSignaB).append(newRacikanSignaB);
            $(newRacikanSignaB).addClass("form-control racikan_signa_b").attr({
                "placeholder": "0"
            }).val(setter.signaTakar).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            var newRacikanJlh = document.createElement("INPUT");
            $(newRacikanCellJlh).append(newRacikanJlh);
            $(newRacikanJlh).addClass("form-control racikan_signa_jlh").attr({
                "placeholder": "0"
            }).val(setter.signaHari).inputmask({
                alias: 'decimal',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            });

            var newRacikanDelete = document.createElement("BUTTON");
            //$(newRacikanCellAksi).append(newRacikanDelete);
            $(newRacikanDelete).addClass("btn btn-danger btn-sm btn-delete-racikan").html("<i class=\"fa fa-ban\"></i>");

            $(newRacikanRow).append(newRacikanCellID);
            $(newRacikanRow).append(newRacikanCellNama);
            $(newRacikanRow).append(newRacikanCellSignaA);
            $(newRacikanRow).append(newRacikanCellSignaX);
            $(newRacikanRow).append(newRacikanCellSignaB);
            $(newRacikanRow).append(newRacikanCellJlh);
            $(newRacikanRow).append(newRacikanCellHarga);
            $(newRacikanRow).append(newRacikanCellAksi);

            $("#table-resep-racikan tbody.racikan").append(newRacikanRow);
            rebaseRacikan();
        }

        function rebaseRacikan() {
            $("#table-resep-racikan > tbody.racikan > tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "row_racikan_" + id
                });

                $(this).find("td:eq(0)").html(id);

                $(this).find("td:eq(1) input.racikan_nama").attr({
                    "id": "racikan_nama_" + id
                });

                if($(this).find("td:eq(1) input") == "") {
                    $(this).find("td:eq(1) input").val("RACIKAN " + id);
                }

                $(this).find("td:eq(1) table").attr({
                    "id": "komposisi_" + id
                });

                $(this).find("td:eq(1) button.tambahKomposisi").attr({
                    "id": "tambah_komposisi_" + id
                });

                $(this).find("td:eq(2) input.racikan_signa_a").attr({
                    "id": "racikan_signaA_" + id
                });

                $(this).find("td:eq(4) input.racikan_signa_b").attr({
                    "id": "racikan_signaB_" + id
                });

                $(this).find("td:eq(5) input").attr({
                    "id": "racikan_jumlah_" + id
                });

                $(this).find("td:eq(6) span").attr({
                    "id": "racikan_harga_" + id
                });

                $(this).find("td:eq(7) button").attr({
                    "id": "racikan_delete_" + id
                });
            });
        }


        function autoKomposisi(id, setter = {}, global_qty = 0) {
            if(setter.obat != undefined || $("#komposisi_" + id + " tbody tr").length == 0 || $("#komposisi_" + id + " tbody tr:last-child td:eq(1)").html() != "") {
                var newKomposisiRow = document.createElement("TR");
                $(newKomposisiRow).addClass("komposisi-row");

                var newKomposisiCellID = document.createElement("TD");
                var newKomposisiCellObat = document.createElement("TD");
                var newKomposisiCellJumlah = document.createElement("TD");
                var newKomposisiCellSatuan = document.createElement("TD");
                var newKomposisiCellAksi = document.createElement("TD");


                $(newKomposisiCellObat).append("<h6></h6><ol></ol>");

                var newKomposisiEdit = document.createElement("BUTTON");
                $(newKomposisiEdit).addClass("btn btn-sm btn-info btn_edit_komposisi").html("<i class=\"fa fa-pencil-alt\"></i>");

                var newKomposisiDelete = document.createElement("BUTTON");
                $(newKomposisiDelete).addClass("btn btn-sm btn-danger btn_delete_komposisi").html("<i class=\"fa fa-ban\"></i>");

                $(newKomposisiCellAksi).append("<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\"></div>");
                $(newKomposisiCellAksi).find("div").append(newKomposisiEdit);
                $(newKomposisiCellAksi).find("div").append(newKomposisiDelete);

                var newJumlahObatRacikanPerItem = document.createElement("INPUT");
                $(newJumlahObatRacikanPerItem).addClass("form-control jlh_obat_racikan").attr({
                    "placeholder": "0"
                }).val(global_qty).inputmask({
                    alias: 'decimal',
                    rightAlign: true,
                    placeholder: "0.00",
                    prefix: "",
                    autoGroup: false,
                    digitsOptional: true
                });

                $(newKomposisiCellJumlah).append(newJumlahObatRacikanPerItem);

                $(newKomposisiRow).append(newKomposisiCellID);
                $(newKomposisiRow).append(newKomposisiCellObat);
                $(newKomposisiRow).append(newKomposisiCellJumlah);
                $(newKomposisiRow).append(newKomposisiCellSatuan);
                $(newKomposisiRow).append(newKomposisiCellAksi);

                $("#komposisi_" + id + " tbody").append(newKomposisiRow);

                /*if($("#komposisi_" + id + " tbody tr").length == 1) {
                    //autoModal
                    prepareModal(id);
                }*/
                if(setter.obat != undefined) {
                    $(newKomposisiCellObat).find("h6").attr({
                        "uid-obat" : setter.obat
                    }).html(setter.obat_detail.nama.toUpperCase());

                    //$(newKomposisiCellJumlah).html(setter.ratio);
                    $(newKomposisiCellSatuan).html(setter.kekuatan);
                } else {
                    prepareModal(id);
                }

                rebaseKomposisi(id);
            }
        }

        function load_product_resep(target, selectedData = "", appendData = true) {
            var selected = [];
            var productData = [];

            $(target).select2({
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
                    url:__HOSTAPI__ + "/Inventori/get_item_select2/" + $(".select2-search__field").val(),
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
                                    id: item.uid,
                                    satuan_terkecil: item.satuan_terkecil.nama
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
                if(data.satuan_terkecil != undefined) {
                    $(this).children("[value=\""+ data.id + "\"]").attr({
                        "satuan-caption": data.satuan_terkecil
                    });
                } else {
                    return false;
                }
            });

            return {
                allow: true,
                data: []
            };
        }

        function rebaseKomposisi(id) {
            $("#komposisi_" + id + " tbody tr").each(function(e) {
                var cid = (e + 1);

                $(this).attr({
                    "id": "single_komposisi_" + cid
                });

                $(this).find("td:eq(0)").html(cid);

                $(this).find("td:eq(1)").attr({
                    "id": "obat_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(1) ol").attr({
                    "id": "obat_komposisi_batch_" + id + "_" + cid
                });

                $(this).find("td:eq(2) input").attr({
                    "id": "jlh_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(3)").attr({
                    "id": "takar_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(4) button:eq(0)").attr({
                    "id": "button_edit_komposisi_" + id + "_" + cid
                });

                $(this).find("td:eq(4) button:eq(1)").attr({
                    "id": "button_delete_komposisi_" + id + "_" + cid
                });

                refreshBatch($(this).find("td:eq(1) h6").attr("uid-obat"), id + "_" + cid, "racikan");
            });
        }

        function prepareModal(id, setData = {
            obat: "",
            jlh: "",
            takarBulat: 1,
            takarDesimal: "",
            kekuatan: ""
        }) {
            $("#form-editor-racikan").modal("show");
            $("#modal-large-title").html($("#racikan_nama_" + id).val());

            //$("#txt_racikan_jlh").val(setData.jlh);
            //$("#txt_racikan_takar").val(setData.takar);
            $("#txt_racikan_takar").val(setData.takarDesimal);
            $("#txt_racikan_takar_bulat").val(setData.takarBulat);
            $("#txt_racikan_kekuatan").val(setData.kekuatan);

            var modalProduct = load_product_resep("#txt_racikan_obat", setData.obat, false);
            var itemData = modalProduct.data;
            var parsedItemData = [];
            for(var dataKey in itemData) {
                var penjaminList = [];
                var penjaminListData = itemData[dataKey].penjamin;
                for(var penjaminKey in penjaminListData) {
                    if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                        penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                    }
                }


                parsedItemData.push({
                    id: itemData[dataKey].uid,
                    "penjamin-list": penjaminList,
                    "satuan-caption": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.nama : "",
                    "satuan-terkecil": (itemData[dataKey].satuan_terkecil != undefined) ? itemData[dataKey].satuan_terkecil.uid : "",
                    text: "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + ";\">" + itemData[dataKey].nama.toUpperCase() + "</div>",
                    html: 	"<div class=\"select2_item_stock\">" +
                        "<div style=\"color:" + ((itemData[dataKey].stok > 0) ? "#12a500" : "#cf0000") + "\">" + itemData[dataKey].nama.toUpperCase() + "</div>" +
                        "<div>" + itemData[dataKey].stok + "</div>" +
                        "</div>",
                    title: itemData[dataKey].nama
                });
            }

            $("#txt_racikan_obat").addClass("form-control resep-obat").select2({
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
                                    "id": item.uid,
                                    "satuan_terkecil": item.satuan_terkecil.nama,
                                    "data-value": item["data-value"],
                                    "penjamin-list": item["penjamin"],
                                    "satuan-caption": item["satuan-caption"],
                                    "satuan-terkecil": item["satuan-terkecil"],
                                    "text": "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + ";\">" + item.nama.toUpperCase() + "</div>",
                                    "html": 	"<div class=\"select2_item_stock\">" +
                                        "<div style=\"color:" + ((item.stok > 0) ? "#12a500" : "#cf0000") + "\">" + item.nama.toUpperCase() + "</div>" +
                                        "<div>" + item.stok + "</div>" +
                                        "</div>",
                                    "title": item.nama
                                }
                            })
                        };
                    }
                },
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
            }).val(setData.obat).trigger("change").on("select2:select", function(e) {
                var data = e.params.data;
                $(this).children("[value=\""+ data["id"] + "\"]").attr({
                    "data-value": data["data-value"],
                    "penjamin-list": data["penjamin-list"],
                    "satuan-caption": data["satuan-caption"],
                    "satuan-terkecil": data["satuan-terkecil"]
                });
            });

            if(setData.obat != "") {
                $("#txt_racikan_obat").append("<option title=\"" + setData.obat_nama + "\" value=\"" + setData.obat + "\">" + setData.obat_nama + "</option>");
                $("#txt_racikan_obat").select2("data", {id: setData.obat, text: setData.obat_nama});
                $("#txt_racikan_obat").trigger("change");
            }
        }

        $("#txt_racikan_obat").select2();
        /*$("#txt_racikan_jlh").inputmask({
            alias: 'decimal',
            rightAlign: true,
            placeholder: "0.00",
            prefix: "",
            autoGroup: false,
            digitsOptional: true
        });*/

        var currentRacikID = 1;
        var currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length;
        var komposisiMode = "add";

        $("body").on("click", ".btn_edit_komposisi", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            prepareModal(Pid, {
                obat: $("#obat_komposisi_" + Pid + "_" + thisID + " h6").attr("uid-obat"),
                obat_nama: $("#obat_komposisi_" + Pid + "_" + thisID + " h6").text(),
                takarBulat: $("#takar_komposisi_" + Pid + "_" + thisID).find("b").html(),
                takarDesimal: $("#takar_komposisi_" + Pid + "_" + thisID).find("sub").html(),
                kekuatan: $("#takar_komposisi_" + Pid + "_" + thisID).html()
            });

            currentKomposisiID = thisID;
            currentRacikID = Pid;
        });

        $("body").on("click", ".btn-delete-racikan", function() {
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            $("#row_racikan_" + thisID).remove();
            rebaseRacikan();
        });

        $("body").on("click", ".btn_delete_komposisi", function(){
            var id = $(this).attr("id").split("_");
            var thisID = id[id.length - 1];
            var Pid = id[id.length - 2];

            $("#single_komposisi_" + thisID).remove();
            rebaseKomposisi(Pid);
            refreshBatch($("#obat_komposisi_" + Pid + "_" + thisID + " h6").attr("uid-obat"), Pid + "_" + thisID, "racikan");
            return false;
        });

        $("body").on("click", ".tambahKomposisi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentRacikID = id;
            currentKomposisiID = $("#komposisi_" + currentRacikID + " tbody tr").length + 1;

            autoKomposisi(id);
        });

        $("body").on("click", "#btnSubmitKomposisi", function() {
            var infoPenjamin = "";
            $("#obat_komposisi_" + currentRacikID + "_" + currentKomposisiID + " h6")
                .html($("#txt_racikan_obat").find("option:selected").text() + infoPenjamin)
                .attr({
                    "uid-obat": $("#txt_racikan_obat").val()
                });
            $("#takar_komposisi_" + currentRacikID + "_" + currentKomposisiID).html($("#txt_racikan_kekuatan").val());
            //Tentukan Batch setelah dipilih
            refreshBatch($("#txt_racikan_obat").val(), currentRacikID + "_" + currentKomposisiID, "racikan");
            $("#form-editor-racikan").modal("hide");
        });

        $("body").on("keyup", ".racikan_signa_a", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".racikan_signa_b", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });

        $("body").on("keyup", ".jlh_obat_racikan", function() {
            var id = $(this).attr("id").split("_");
            group = id[id.length - 2];
            id = id[id.length - 1];

            if($(this).inputmask("unmaskedvalue") < 1) {
                $(this).val(1);
            }

            refreshBatch($("#obat_komposisi_" + group + "_" + id + " h6").attr("uid-obat"), group + "_" + id, "racikan");

            checkGenerateRacikan(group);
        });

        /*$("body").on("keyup", ".racikan_signa_jlh", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            checkGenerateRacikan(id);
        });*/

        //===========================================================================
        $("body").on("keyup", ".resep_konsumsi", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            //checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_takar", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            //checkGenerateResep(id);
        });

        $("body").on("keyup", ".resep_jlh_hari", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            //checkGenerateResep(id);
        });

        $("body").on("select2:select", ".resep-obat", function(e) {
            var data = e.params.data;
            $(this).children("[value=\""+ data["id"] + "\"]").attr({
                "data-value": data["data-value"],
                "penjamin-list": data["penjamin-list"],
                "satuan-caption": data["satuan-caption"],
                "satuan-terkecil": data["satuan-terkecil"]
            });

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if($(this).val() != "none") {
                var dataKategoriPerObat = autoKategoriObat(data['id']);
                var kategoriObatDOM = "";
                if(dataKategoriPerObat.length > 0) {
                    for(var kategoriObatKey in dataKategoriPerObat) {
                        if(
                            dataKategoriPerObat[kategoriObatKey].kategori !== undefined &&
                            dataKategoriPerObat[kategoriObatKey].kategori !== null
                        ) {
                            kategoriObatDOM += "<span class=\"badge badge-info resep-kategori-obat\">" + dataKategoriPerObat[kategoriObatKey].kategori.nama + "</span>";
                        }
                    }
                }

                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />" + kategoriObatDOM);

                var penjaminAvailable = ($(this).find("option:selected").attr("penjamin-list") !== undefined) ? $(this).find("option:selected").attr("penjamin-list").split(",") : [];
                //checkPenjaminAvail(pasien_penjamin_uid, penjaminAvailable, id);

                var satuanCaption = $(this).find("option:selected").attr("satuan-caption");
                $("#resep_satuan_" + id).html(satuanCaption);
                rebaseResep();
            } else {
                $("#resep_obat_" + id).parent().find("div.penjamin-container").html("");
                $("#resep_satuan_" + id).html("");
                $("#resep_row_" + id).find("td:eq(1) div.kategori-obat-container").html("<span>Kategori Obat</span><br />");
            }
        });

        $("body").on("click", ".resep_delete", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            if(!$("#resep_row_" + id).hasClass("last-resep")) {
                $("#resep_row_" + id).remove();
            }

            rebaseResep();
            //$("#table-resep tbody tr").each(function(e));
        });

        function checkGenerateRacikan(id = 0) {
            if($(".last-racikan").length === 0) {
                //autoRacikan();
                //alert();
                //alert();
            } else {
                var obat = $("#racikan_nama_" + id).val();
                var jlh_obat = $("#racikan_jumlah_" + id).inputmask("unmaskedvalue");
                var signa_konsumsi = $("#racikan_signaA_" + id).inputmask("unmaskedvalue");
                var signa_hari = $("#racikan_signaB_" + id).inputmask("unmaskedvalue");

                if(
                    parseFloat(jlh_obat) > 0 &&
                    parseFloat(signa_konsumsi) > 0 &&
                    parseFloat(signa_hari) > 0 &&
                    obat != null &&
                    $("#row_racikan_" + id).hasClass("last-racikan")
                ) {

                    autoRacikan();
                }
            }
        }

        $("#btnSelesai").click(function() {
            Swal.fire({
                title: "Verfikasi Resep",
                text: "Pastikan semua obat sudah sesuai dan stok mencukupi. Data sudah benar?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    //Populate Resep

                    var allowSave = false;

                    var resepItem = [];
                    $("#table-resep tbody tr").each(function() {
                        var obat = $(this).find("td:eq(1) select:eq(0)").val();
                        if(obat !== null) {
                            if($(this).find("td:eq(1) ol li").length === 0) {
                                allowSave = false;
                                return false;
                            } else {
                                allowSave = true;
                            }

                            resepItem.push({
                                "obat": $(this).find("td:eq(1) select:eq(0)").val(),
                                "signa_qty": parseFloat($(this).find("td:eq(2) input").inputmask("unmaskedvalue")),
                                "signa_pakai": parseFloat($(this).find("td:eq(4) input").inputmask("unmaskedvalue")),
                                "jumlah": parseFloat($(this).find("td:eq(5) input").inputmask("unmaskedvalue")),
                                "harga": parseFloat($(this).find("td:eq(1) ol").attr("harga")),
                                "aturan_pakai": $(this).find("td:eq(1) select:eq(1)").val(),
                                "keterangan": $(this).find("td:eq(1) textarea").val()
                            });
                        }
                    });

                    var racikanItem = [];
                    $("#table-resep-racikan > tbody > tr").each(function() {
                        var racikan_nama = $(this).find("td:eq(1) input").val();
                        if(racikan_nama !== undefined && racikan_nama !== "") {
                            var komposisi = [];
                            $(this).find("td:eq(1) table tbody tr").each(function() {
                                var hargaPerObatRacikan = 0;
                                if($(this).find("td:eq(1) ol").length > 0) {
                                    hargaPerObatRacikan = $(this).find("td:eq(1) ol").attr("harga");

                                    if($(this).find("td:eq(1) ol li").length === 0) {
                                        allowSave = false;
                                        return false;
                                    } else {
                                        allowSave = true;
                                    }
                                }

                                komposisi.push({
                                    "obat": $(this).find("td:eq(1) h6").attr("uid-obat"),
                                    "jumlah": $(this).find("td:eq(2) input").inputmask("unmaskedvalue"),
                                    "kekuatan": $(this).find("td:eq(3)").html(),
                                    "harga": parseFloat(hargaPerObatRacikan)
                                });
                            });

                            racikanItem.push({
                                "racikan_uid": $(this).attr("uid"),
                                "racikan_nama": racikan_nama,
                                "racikan_komposisi": komposisi,
                                "aturan_pakai": $(this).find("td:eq(1) select").val(),
                                "keterangan": $(this).find("td:eq(1) textarea").val(),
                                "signa_qty": parseFloat($(this).find("td.master-racikan-cell:eq(2) input").inputmask("unmaskedvalue")),
                                "signa_pakai": parseFloat($(this).find("td.master-racikan-cell:eq(4) input").inputmask("unmaskedvalue")),
                                "harga": parseFloat($(this).find("td.master-racikan-cell:eq(6) span").html().replace(/(,)/g, "")),
                                "jumlah": parseFloat($(this).find("td.master-racikan-cell:eq(5) input").inputmask("unmaskedvalue"))
                            });
                        }
                    });

                    if(allowSave) {
                        $.ajax({
                            url:__HOSTAPI__ + "/Apotek",
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"POST",
                            data:{
                                request: "verifikasi_resep_2",
                                uid: __PAGES__[3],
                                kunjungan: currentMetaData.kunjungan,
                                antrian:currentMetaData.uid,
                                pasien:currentMetaData.pasien.uid,
                                penjamin: currentMetaData.penjamin.uid,
                                resep: resepItem,
                                racikan: racikanItem,
                                departemen: currentMetaData.departemen.uid
                            },
                            success:function(response) {
                                if(response.response_package.antrian.response_result > 0) {
                                    if(currentMetaData.penjamin.uid === __UIDPENJAMINUMUM__) {
                                        Swal.fire(
                                            "Verifikasi Berhasil!",
                                            "Silahkan pasien menuju kasir",
                                            "success"
                                        ).then((result) => {
                                            push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya obat baru", "warning").then(function() {
                                                location.href = __HOSTNAME__ + "/apotek/resep/";
                                            });
                                        });
                                    } else {
                                        Swal.fire(
                                            "Verifikasi Berhasil!",
                                            "Silahkan minta pasien menunggu proses persiapan obat",
                                            "success"
                                        ).then((result) => {
                                            push_socket(__ME__, "antrian_apotek_baru", "*", "Permintaan Resep Baru BPJS", "warning").then(function() {
                                                location.href = __HOSTNAME__ + "/apotek/resep/";
                                            });
                                        });
                                    }
                                }
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    } else {
                        Swal.fire(
                            "Verifikasi Gagal!",
                            "Pastikan semua obat memiliki stok tersedia",
                            "warning"
                        ).then((result) => {
                            //location.href = __HOSTNAME__ + "/apotek/resep/";
                        });
                    }
                }
            });
        });

        $("#btnCopyResep").click(function() {
            //
        });
    });
</script>

<div id="form-editor-racikan" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Order Laboratorium</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_racikan_obat">Obat:</label>
                    <select class="form-control" id="txt_racikan_obat"></select>
                </div>
                <!-- <div class="form-group col-md-6">
                    <label for="txt_racikan_jlh">Jumlah:</label>
                    <input type="text" class="form-control" id="txt_racikan_jlh" />
                </div> -->
                <div class="form-group col-md-12">
                    <div class="kolom_kekuatan">
                        <label for="txt_racikan_kekuatan">Kekuatan:</label>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" class="form-control" id="txt_racikan_kekuatan" placeholder="0" />
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="kolom_takar" style="display: none">
                        <label for="txt_racikan_takar">Takar:</label>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" value="1" class="form-control" id="txt_racikan_takar_bulat" placeholder="0" />
                            </div>
                            <div class="col-md-1">
                                <i class="fa fa-plus" style="margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="txt_racikan_takar" placeholder="a/b" />
                            </div>
                            <div class="col-md-3">
                                <small>Cth:<br />2 + 1/2</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="form-group col-md-12">
                    <label for="txt_racikan_satuan">Satuan:</label>
                    <select class="form-control" id="txt_racikan_satuan"></select>
                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitKomposisi">Order</button>
            </div>
        </div>
    </div>
</div>