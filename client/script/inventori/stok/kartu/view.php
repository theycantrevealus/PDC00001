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

                        $(batchIdentifierInfo).html("<span class=\"badge badge-custom-caption badge-info\" style=\"margin-left: 10px;\">" + batchGroup[a].batch_info.batch + " [" + batchGroup[a].batch_info.expired_date_parsed + "]</span>");


                        for(var b in batchGroup[a].log) {
                            var newRow = document.createElement("TR");
                            var newTgl = document.createElement("TD");
                            var newDoc = document.createElement("TD");
                            var newUraian = document.createElement("TD");
                            var newMasuk = document.createElement("TD");
                            var newKeluar = document.createElement("TD");
                            var newSaldo = document.createElement("TD");
                            var newKeterangan = document.createElement("TD");

                            $(newTgl).html("<b>" + batchGroup[a].log[b].logged_at + "</b>");
                            $(newDoc).html("<span class=\"wrap_content\">" + batchGroup[a].log[b].dokumen + "</span>");
                            //$(newUraian).html(batchGroup[a].log[b].batch.batch);
                            $(newMasuk).html(number_format(batchGroup[a].log[b].masuk, 2, ",", ".")).addClass("number_style");
                            $(newKeluar).html(number_format(batchGroup[a].log[b].keluar, 2, ",", ".")).addClass("number_style");
                            $(newSaldo).html(number_format(batchGroup[a].log[b].saldo, 2, ",", ".")).addClass("number_style");
                            $(newKeterangan).html("<span>Stok " + ((parseFloat(batchGroup[a].log[b].masuk) === 0) ? "Keluar <i class=\"fa fa-arrow-alt-circle-up\"></i>" : "Masuk <i class=\"fa fa-arrow-alt-circle-down\"></i>") + "</span>" +
                                "<p style=\"padding: 10px 5px\"><b>Keterangan:</b><br />" + batchGroup[a].log[b].keterangan + "</p>");

                            $(newRow).append(newTgl);
                            $(newRow).append(newDoc);
                            //$(newRow).append(newUraian);
                            $(newRow).append(newMasuk);
                            $(newRow).append(newKeluar);
                            $(newRow).append(newSaldo);
                            $(newRow).append(newKeterangan);

                            if(parseFloat(batchGroup[a].log[b].masuk) === 0) {
                                $(newRow).find("td:eq(5) span").addClass("badge badge-warning badge-custom-caption");
                            } else if(parseFloat(batchGroup[a].log[b].keluar) === 0) {
                                $(newRow).find("td:eq(5) span").addClass("badge badge-success badge-custom-caption");
                            } else {
                                //
                            }

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

                        $(batchTable).append(tbodyContainer).css({
                            "margin-bottom": "30px"
                        });


                        $("#loadResult").append(batchIdentifierInfo).append("<br />").append(batchTable);
                        if($(batchTable).find("tbody tr").length === 0) {
                            $(batchTable).find("tbody").append("<tr><td colspan=\"6\"><center><i>Tidak ada dapa</i></center></td></tr>")
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