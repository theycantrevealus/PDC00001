<script type="text/javascript">
    $(function () {
        let targetID = __PAGES__[4];

        $("#range_stok").change(function() {
            refresh_kartu();
        });

        refresh_kartu();



        function getDateRange(target) {
            var rangeKartu = $(target).val().split(" to ");
            if(rangeKartu.length > 1) {
                return rangeKartu;
            } else {
                return [rangeKartu, rangeKartu];
            }
        }

        function refresh_kartu() {
            $("#loadResult").html("<center>Memuat Data...</center>");
            //Get Item Detail
            $.ajax({
                url:__HOSTAPI__ + "/Inventori/kartu_stok/" + targetID + "/" + __UNIT__.gudang + "/" + getDateRange("#range_stok")[0] + "/" + getDateRange("#range_stok")[1],
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(resp) {
                    $("#loadResult").html("");
                    var data = resp.response_package.response_data[0];

                    $("#nama_barang").html(data.nama);
                    $("#item_name").html(data.nama.toUpperCase());
                    $("#kemasan_barang").html(data.satuan_terkecil_info.nama);



                    //Group the batch
                    var batchGroup = {};

                    for(var a in data.log) {
                        if(batchGroup[data.log[a].batch.uid] === undefined) {
                            batchGroup[data.log[a].batch.uid] = {
                                log: [],
                                batch_info: batchGroup[data.log[a].batch.uid] = data.log[a].batch
                            }
                        }
                        batchGroup[data.log[a].batch.uid].log.push(data.log[a]);
                    }

                    for(var a in batchGroup) {
                        var batchTable = document.createElement("TABLE");
                        var batchIdentifierInfo = document.createElement("H5");

                        var theadGroup = document.createElement("THEAD");
                        var theadRow = document.createElement("TR");
                        var theadTanggal = document.createElement("TH");
                        var theadDokumen = document.createElement("TH");
                        var theadMasuk = document.createElement("TH");
                        var theadKeluar = document.createElement("TH");
                        var theadSaldo = document.createElement("TH");
                        var theadKeterangan = document.createElement("TH");


                        $(theadTanggal).css({
                            "width": "10%"
                        }).html("Tanggal");

                        $(theadDokumen).addClass("wrap_content").css({
                            "min-width": "100px"
                        }).html("Dokumen");
                        $(theadMasuk).addClass("wrap_content").html("Masuk").css({
                            "min-width": "80px"
                        });
                        $(theadKeluar).addClass("wrap_content").html("Keluar").css({
                            "min-width": "80px"
                        });
                        $(theadSaldo).addClass("wrap_content").html("Saldo").css({
                            "min-width": "80px"
                        });
                        $(theadKeterangan).html("Keterangan");

                        $(theadRow).append(theadTanggal);
                        $(theadRow).append(theadDokumen);
                        $(theadRow).append(theadMasuk);
                        $(theadRow).append(theadKeluar);
                        $(theadRow).append(theadSaldo);
                        $(theadRow).append(theadKeterangan);
                        $(theadGroup).append(theadRow).addClass("thead-dark");
                        $(batchTable).append(theadGroup).addClass("table table-bordered largeDataType");

                        var tbodyContainer = document.createElement("TBODY");

                        $(batchIdentifierInfo).html("<span class=\"badge badge-custom-caption badge-outline-info\" style=\"margin-left: 10px;\">" + batchGroup[a].batch_info.batch + " [" + batchGroup[a].batch_info.expired_date_parsed + "]</span>");


                        for(var b in batchGroup[a].log) {
                            var newRow = document.createElement("TR");
                            var newTgl = document.createElement("TD");
                            var newDoc = document.createElement("TD");
                            var newUraian = document.createElement("TD");
                            var newMasuk = document.createElement("TD");
                            var newKeluar = document.createElement("TD");
                            var newSaldo = document.createElement("TD");
                            var newKeterangan = document.createElement("TD");

                            $(newTgl).html("<b>" + batchGroup[a].log[b].logged_at + "</b>").addClass("text-right");
                            $(newDoc).html("<span class=\"wrap_content\">" + batchGroup[a].log[b].dokumen + "</span>");
                            //$(newUraian).html(batchGroup[a].log[b].batch.batch);
                            $(newMasuk).html("<h5 class=\"" + ((parseFloat(batchGroup[a].log[b].masuk) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].masuk, 2, ",", ".") + "</h5>").addClass("number_style");
                            $(newKeluar).html("<h5 class=\"" + ((parseFloat(batchGroup[a].log[b].keluar) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].keluar, 2, ",", ".") + "</h5>").addClass("number_style");
                            $(newSaldo).html("<h5 class=\"text-orange\">" + number_format(batchGroup[a].log[b].saldo, 2, ",", ".") + "</h5>").addClass("number_style");
                            if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                                $(newKeterangan).html("<div class=\"row\">" +
                                    "<div class=\"col-lg-2\">" +
                                    "<span><i data-v-da9425c4=\"\" class=\"material-icons\">chrome_reader_mode</i> Opname</span>" +
                                    "</div>" +
                                    "<div class=\"col-lg-8\">" +
                                    "<p style=\"padding: 10px 5px\"><b class=\"text-muted\">Keterangan:</b><br />" + batchGroup[a].log[b].keterangan + "</p>" +
                                    "</div>" +
                                    "<div class=\"col-lg-2\">" +
                                    batchGroup[a].log[b].type.nama +
                                    "</div>" +
                                    "</div>");
                            } else {
                                $(newKeterangan).html("<div class=\"row\">" +
                                    "<div class=\"col-lg-2\">" +
                                    "<span>" + ((parseFloat(batchGroup[a].log[b].masuk) === 0) ? "<i data-v-da9425c4=\"\" class=\"material-icons\">arrow_upward</i> Stok Keluar" : "<i data-v-da9425c4=\"\" class=\"material-icons\">arrow_downward</i> Stok Masuk") + "</span>" +
                                    "</div>" +
                                    "<div class=\"col-lg-8\">" +
                                    "<p style=\"padding: 10px 5px\"><b class=\"text-muted\">Keterangan:</b><br />" + batchGroup[a].log[b].keterangan + "</p>" +
                                    "</div>" +
                                    "<div class=\"col-lg-2\">" +
                                    batchGroup[a].log[b].type.nama +
                                    "</div>" +
                                    "</div>");
                            }

                            $(newRow).append(newTgl);
                            $(newRow).append(newDoc);
                            //$(newRow).append(newUraian);
                            $(newRow).append(newMasuk);
                            $(newRow).append(newKeluar);
                            $(newRow).append(newSaldo);
                            $(newRow).append(newKeterangan);

                            if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                                $(newRow).find("td:eq(5) span").addClass("badge badge-outline-purple badge-custom-caption");
                            } else {
                                /*if(
                                    batchGroup[a].log[b].type.id === __AMPRAH_OPNAME_IN__ || batchGroup[a].log[b].type.id === __AMPRAH_OPNAME_OUT__ ||
                                    batchGroup[a].log[b].type.id === __STATUS_BARANG_MASUK_OPNAME__ || batchGroup[a].log[b].type.id === __STATUS_BARANG_KELUAR_OPNAME__
                                ) {
                                    $(newRow).find("td:eq(5) span").addClass("badge badge-outline-purple badge-custom-caption");
                                } else {

                                }*/
                                if(parseFloat(batchGroup[a].log[b].masuk) === 0) {
                                    $(newRow).find("td:eq(5) span").addClass("badge badge-outline-warning badge-custom-caption");
                                } else if(parseFloat(batchGroup[a].log[b].keluar) === 0) {
                                    $(newRow).find("td:eq(5) span").addClass("badge badge-outline-success badge-custom-caption");
                                } else {
                                    //
                                }
                            }

                            if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                                $(tbodyContainer).append(newRow);
                            } else {
                                if(
                                    parseFloat(batchGroup[a].log[b].masuk) === 0 &&
                                    parseFloat(batchGroup[a].log[b].keluar) === 0 &&
                                    parseFloat(batchGroup[a].log[b].saldo) === 0
                                ) {
                                    //
                                } else {
                                    $(tbodyContainer).append(newRow);
                                }
                            }

                            if(
                                /*batchGroup[a].log[b].type.id === __AMPRAH_OPNAME_IN__ || batchGroup[a].log[b].type.id === __AMPRAH_OPNAME_OUT__ ||
                                batchGroup[a].log[b].type.id === __STATUS_BARANG_MASUK_OPNAME__ || batchGroup[a].log[b].type.id === __STATUS_BARANG_KELUAR_OPNAME__ ||*/
                                batchGroup[a].log[b].type.id === __STATUS_OPNAME__
                            ) {
                                $(newRow).find("td:eq(0)").addClass("opname_card_stock_transact");
                            }
                        }

                        $(batchTable).append(tbodyContainer).css({
                            "margin-bottom": "30px"
                        });

                        if(batchGroup[a].log.length > 0) {
                            $("#loadResult").append(batchIdentifierInfo).append("<br />").append(batchTable);
                        }

                        if($(batchTable).find("tbody tr").length === 0) {
                            $(batchTable).find("tbody").append("<tr><td colspan=\"6\"><center><i>Tidak ada data</i></center></td></tr>")
                        }
                        /*var newRow = document.createElement("TR");
                        var newTgl = document.createElement("TD");
                        var newDoc = document.createElement("TD");
                        var newUraian = document.createElement("TD");
                        var newMasuk = document.createElement("TD");
                        var newKeluar = document.createElement("TD");
                        var newSaldo = document.createElement("TD");
                        var newKeterangan = document.createElement("TD");

                        $(newTgl).html(data.log[a].logged_at);
                        $(newDoc).html(data.log[a].dokumen);
                        $(newUraian).html(data.log[a].batch.batch);
                        $(newMasuk).html(number_format(data.log[a].masuk, 0, ",", ".")).addClass("number_style");
                        $(newKeluar).html(number_format(data.log[a].keluar, 0, ",", ".")).addClass("number_style");
                        $(newSaldo).html(number_format(data.log[a].saldo, 0, ",", ".")).addClass("number_style");
                        $(newKeterangan).html(data.log[a].keterangan);

                        $(newRow).append(newTgl);
                        $(newRow).append(newDoc);
                        $(newRow).append(newUraian);
                        $(newRow).append(newMasuk);
                        $(newRow).append(newKeluar);
                        $(newRow).append(newSaldo);
                        $(newRow).append(newKeterangan);

                        $("#table-item-log tbody").append(newRow);*/
                    }
                },
                error: function(resp) {
                    console.log(resp);
                }
            });
        }
    });
</script>