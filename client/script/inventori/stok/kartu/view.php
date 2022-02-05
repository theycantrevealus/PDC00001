<script src="<?php echo __HOSTNAME__; ?>/plugins/chartjs/chart.min.js"></script>
<script type="text/javascript">
    $(function () {
        let targetID = __PAGES__[4];

        var actLib = {
            "D": "<i class=\"fa fa-trash text-danger\"></i>",
            "U": "<i class=\"fa fa-edit text-warning\"></i>",
            "I": "<i class=\"fa fa-plus-circle text-success\"></i>"
        };

        var configOption = {
            plugins: {
                legend: {
                    display: true
                }
            },
            scale: {
                ticks: {
                    display: false,
                    maxTicksLimit: 0
                }
            }
        };

        var ctx = document.getElementById("currentStokGraph").getContext("2d");

        var myNewChart = new Chart(ctx, {
            type: "line",
            data: {
                labels: [],
                datasets: []
            },
            options: configOption
        });

        refreshData(myNewChart);

        function refreshData(myNewChart) {
            var forReturn;
            $.ajax({
                url: __HOSTAPI__ + "/Inventori",
                async: false,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: "stok_activity",
                    item: targetID,
                    from: getDateRange("#range_stok")[0],
                    to: getDateRange("#range_stok")[1]
                },
                success: function (response) {
                    var data = response.response_package;
                    if(data !== undefined && data !== null) {
                        forReturn = data;
                        myNewChart.data = forReturn;
                        myNewChart.update();
                    }
                },
                error: function (response) {
                    //
                }
            });
        }





























        $("#range_stok").change(function() {
            refresh_kartu();
            refreshData(myNewChart);
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
                        if(data.log[a].batch !== null) {
                            if(batchGroup[data.log[a].batch.uid] === undefined) {
                                batchGroup[data.log[a].batch.uid] = {
                                    log: [],
                                    batch_info: batchGroup[data.log[a].batch.uid] = data.log[a].batch
                                }
                            }
                            batchGroup[data.log[a].batch.uid].log.push(data.log[a]);
                        }
                    }

                    var batchTable = document.createElement("TABLE");
                    var theadGroup = document.createElement("THEAD");
                    var theadRow = document.createElement("TR");
                    var theadTanggal = document.createElement("TH");
                    var theadDokumen = document.createElement("TH");
                    var theadBatch = document.createElement("TH");
                    var theadED = document.createElement("TH");
                    var theadMasuk = document.createElement("TH");
                    var theadKeluar = document.createElement("TH");
                    var theadSaldo = document.createElement("TH");
                    var theadKeterangan = document.createElement("TH");
                    var tbodyContainer = document.createElement("TBODY");

                    $(theadGroup).addClass("thead-dark");
                    $(theadTanggal).css({
                        "width": "10%"
                    }).html("Tanggal");
                    $(theadBatch).addClass("wrap_content").css({
                        "min-width": "100px"
                    }).html("Batch");
                    $(theadED).addClass("wrap_content").css({
                        "min-width": "100px"
                    }).html("ED");
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


                    $("#loadResult").append(batchTable);
                    $(batchTable).append(theadGroup);
                    $(theadGroup).append(theadRow);
                    $(theadRow).append(theadTanggal);
                    $(theadRow).append(theadBatch);
                    $(theadRow).append(theadED);
                    $(theadRow).append(theadMasuk);
                    $(theadRow).append(theadKeluar);
                    $(theadRow).append(theadSaldo);
                    $(theadRow).append(theadDokumen);
                    $(theadRow).append(theadKeterangan);
                    $(batchTable).append(tbodyContainer);

                    $(batchTable).addClass("table table-bordered largeDataType")
                    
                    
                    

                    var totalAllStock = 0;
                    var m = today.getMonth() + 1;
                    var d = today.getDay();
                    var y = today.getFullYear();

                    var todayUnparsed = new Date(y,m,d);

                    for(var a in batchGroup) {
                        var eachBatchTotal = 0;
                        for(var b in batchGroup[a].log) {
                            var checkED = new Date(batchGroup[a].batch_info.expired_date);
                            var newRow = document.createElement("TR");
                            var newTgl = document.createElement("TD");
                            var newBatch = document.createElement("TD");
                            var newED = document.createElement("TD");
                            var newDoc = document.createElement("TD");
                            var newUraian = document.createElement("TD");
                            var newMasuk = document.createElement("TD");
                            var newKeluar = document.createElement("TD");
                            var newSaldo = document.createElement("TD");
                            var newKeterangan = document.createElement("TD");

                            $(newRow).append(newTgl);
                            $(newRow).append(newBatch);
                            $(newRow).append(newED);
                            $(newRow).append(newMasuk);
                            $(newRow).append(newKeluar);
                            $(newRow).append(newSaldo);
                            $(newRow).append(newDoc);
                            $(newRow).append(newKeterangan);

                            $(newTgl).html("<b>" + batchGroup[a].log[b].logged_at + "</b>").addClass("wrap_content");
                            $(newDoc).html("<span class=\"wrap_content\">" + batchGroup[a].log[b].dokumen + "</span>");
                            $(newBatch).html("<span class=\"wrap_content\">" + batchGroup[a].batch_info.batch + "</span>");
                            
                            $(newED).html(batchGroup[a].batch_info.expired_date_parsed).addClass("wrap_content");
                            if(checkED === todayUnparsed) {
                                $(newED).addClass("text-warning").prepend("<i class=\"fa fa-exclamation\"></i> ");
                            } else if(checkED < todayUnparsed) {
                                $(newED).addClass("text-danger").prepend("<i class=\"fa fa-ban\"></i> ");
                            } else {
                                $(newED).addClass("text-success").prepend("<i class=\"fa fa-check-circle\"></i> ");
                            }

                            $(newMasuk).html("<h6 class=\"number_style " + ((parseFloat(batchGroup[a].log[b].masuk) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].masuk, 2, ",", ".") + "</h6>").addClass("number_style");
                            $(newKeluar).html("<h6 class=\"number_style " + ((parseFloat(batchGroup[a].log[b].keluar) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].keluar, 2, ",", ".") + "</h6>").addClass("number_style");
                            $(newSaldo).html("<h6 class=\"number_style\" rawval=\"" + batchGroup[a].log[b].saldo + "\">" + number_format(batchGroup[a].log[b].saldo, 2, ",", ".") + "</h6>").addClass("number_style");
                            $(newKeterangan).html(((batchGroup[a].log[b].type.id === __STATUS_OPNAME__) ? "[OPNAME]" : "<span><strong>" + ((parseFloat(batchGroup[a].log[b].masuk) === 0) ? "<i data-v-da9425c4=\"\" class=\"material-icons text-danger\">arrow_upward</i> Stok Keluar" : "<i data-v-da9425c4=\"\" class=\"material-icons text-success\">arrow_downward</i> Stok Masuk") + "</strong></span>") + "<br />" + batchGroup[a].log[b].keterangan);

                            $(tbodyContainer).append(newRow);
                            
                            eachBatchTotal = parseFloat(batchGroup[a].log[b].saldo);
                        }
                        $(newSaldo).find("h6").addClass("text-info");
                        var newRowSplitter = document.createElement("TR");
                        var newCellSplitter = document.createElement("TD");
                        $(newCellSplitter).attr({
                            "colspan": 9,
                            "height": "100px"
                        });
                        $(newRowSplitter).append(newCellSplitter);
                        $(tbodyContainer).append(newRowSplitter);
                        totalAllStock += eachBatchTotal;
                    }



                    // for(var a in batchGroup) {
                    //     var batchTable = document.createElement("TABLE");
                    //     var batchIdentifierInfo = document.createElement("H5");

                    //     var theadGroup = document.createElement("THEAD");
                    //     var theadRow = document.createElement("TR");
                    //     var theadTanggal = document.createElement("TH");
                    //     var theadDokumen = document.createElement("TH");
                    //     var theadMasuk = document.createElement("TH");
                    //     var theadKeluar = document.createElement("TH");
                    //     var theadSaldo = document.createElement("TH");
                    //     var theadKeterangan = document.createElement("TH");


                    //     $(theadTanggal).css({
                    //         "width": "10%"
                    //     }).html("Tanggal");

                    //     $(theadDokumen).addClass("wrap_content").css({
                    //         "min-width": "100px"
                    //     }).html("Dokumen");
                    //     $(theadMasuk).addClass("wrap_content").html("Masuk").css({
                    //         "min-width": "80px"
                    //     });
                    //     $(theadKeluar).addClass("wrap_content").html("Keluar").css({
                    //         "min-width": "80px"
                    //     });
                    //     $(theadSaldo).addClass("wrap_content").html("Saldo").css({
                    //         "min-width": "80px"
                    //     });
                    //     $(theadKeterangan).html("Keterangan");

                    //     $(theadRow).append(theadTanggal);
                    //     $(theadRow).append(theadDokumen);
                    //     $(theadRow).append(theadMasuk);
                    //     $(theadRow).append(theadKeluar);
                    //     $(theadRow).append(theadSaldo);
                    //     $(theadRow).append(theadKeterangan);
                    //     $(theadGroup).append(theadRow).addClass("thead-dark");
                    //     $(batchTable).append(theadGroup).addClass("table table-bordered largeDataType");

                    //     var tbodyContainer = document.createElement("TBODY");

                    //     $(batchIdentifierInfo).html("<span class=\"badge badge-custom-caption badge-outline-info\" style=\"margin-left: 10px;\">" + batchGroup[a].batch_info.batch + " [" + batchGroup[a].batch_info.expired_date_parsed + "]</span>");


                    //     for(var b in batchGroup[a].log) {
                    //         var newRow = document.createElement("TR");
                    //         var newTgl = document.createElement("TD");
                    //         var newDoc = document.createElement("TD");
                    //         var newUraian = document.createElement("TD");
                    //         var newMasuk = document.createElement("TD");
                    //         var newKeluar = document.createElement("TD");
                    //         var newSaldo = document.createElement("TD");
                    //         var newKeterangan = document.createElement("TD");

                    //         $(newTgl).html("<b>" + batchGroup[a].log[b].logged_at + "</b>").addClass("text-right");
                    //         $(newDoc).html("<span class=\"wrap_content\">" + batchGroup[a].log[b].dokumen + "</span>");
                    //         $(newMasuk).html("<h6 class=\"number_style " + ((parseFloat(batchGroup[a].log[b].masuk) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].masuk, 2, ",", ".") + "</h6>").addClass("number_style");
                    //         $(newKeluar).html("<h6 class=\"number_style " + ((parseFloat(batchGroup[a].log[b].keluar) > 0) ? "" : "text-muted") + "\">" + number_format(batchGroup[a].log[b].keluar, 2, ",", ".") + "</h6>").addClass("number_style");
                    //         $(newSaldo).html("<h5 class=\"number_style text-orange\" rawval=\"" + batchGroup[a].log[b].saldo + "\">" + number_format(batchGroup[a].log[b].saldo, 2, ",", ".") + "</h5>").addClass("number_style");
                    //         if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                    //             $(newKeterangan).html("<div class=\"row\">" +
                    //                 "<div class=\"col-lg-2\">" +
                    //                 "<span><i data-v-da9425c4=\"\" class=\"material-icons\">chrome_reader_mode</i> Opname</span>" +
                    //                 "</div>" +
                    //                 "<div class=\"col-lg-8\">" +
                    //                 "<p style=\"padding: 10px 5px\"><b class=\"text-muted\">Keterangan:</b><br />" + batchGroup[a].log[b].keterangan + "</p>" +
                    //                 "</div>" +
                    //                 "<div class=\"col-lg-2\">" +
                    //                 batchGroup[a].log[b].type.nama +
                    //                 "</div>" +
                    //                 "</div>");
                    //         } else {
                    //             $(newKeterangan).html("<div class=\"row\">" +
                    //                 "<div class=\"col-lg-2\">" +
                    //                 "<span><strong>" + ((parseFloat(batchGroup[a].log[b].masuk) === 0) ? "<i data-v-da9425c4=\"\" class=\"material-icons\">arrow_upward</i> Stok Keluar" : "<i data-v-da9425c4=\"\" class=\"material-icons\">arrow_downward</i> Stok Masuk") + "</strong></span>" +
                    //                 "</div>" +
                    //                 "<div class=\"col-lg-8\">" +
                    //                 "<p style=\"padding: 10px 5px\"><b class=\"text-muted\">Keterangan:</b><br />" + batchGroup[a].log[b].keterangan + "</p>" +
                    //                 "</div>" +
                    //                 "<div class=\"col-lg-2\">" +
                    //                 batchGroup[a].log[b].type.nama +
                    //                 "</div>" +
                    //                 "</div>");
                    //         }

                    //         $(newRow).append(newTgl);
                    //         $(newRow).append(newDoc);
                    //         $(newRow).append(newMasuk);
                    //         $(newRow).append(newKeluar);
                    //         $(newRow).append(newSaldo);
                    //         $(newRow).append(newKeterangan);

                    //         if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                    //             $(newRow).find("td:eq(5) span").addClass("badge badge-outline-purple badge-custom-caption");
                    //         } else {
                    //             if(parseFloat(batchGroup[a].log[b].masuk) === 0) {
                    //                 $(newRow).find("td:eq(5) span").addClass("text-warning");
                    //             } else if(parseFloat(batchGroup[a].log[b].keluar) === 0) {
                    //                 $(newRow).find("td:eq(5) span").addClass("text-success");
                    //             } else {
                    //                 //
                    //             }
                    //         }

                    //         if(batchGroup[a].log[b].type.id === __STATUS_OPNAME__) {
                    //             $(tbodyContainer).append(newRow);
                    //         } else {
                    //             if(
                    //                 parseFloat(batchGroup[a].log[b].masuk) === 0 &&
                    //                 parseFloat(batchGroup[a].log[b].keluar) === 0 &&
                    //                 parseFloat(batchGroup[a].log[b].saldo) === 0
                    //             ) {
                    //                 //
                    //             } else {
                    //                 $(tbodyContainer).append(newRow);
                    //             }
                    //         }

                    //         if(
                    //             batchGroup[a].log[b].type.id === __STATUS_OPNAME__
                    //         ) {
                    //             $(newRow).find("td:eq(0)").addClass("opname_card_stock_transact");
                    //         }
                    //     }

                    //     $(batchTable).append(tbodyContainer).css({
                    //         "margin-bottom": "30px"
                    //     }).addClass("singleTable");

                        

                    //     if($(batchTable).find("tbody tr").length === 0) {
                    //         $(batchTable).find("tbody").append("<tr><td colspan=\"6\"><center><i>Tidak ada data</i></center></td></tr>")
                    //     } else {
                    //         if(batchGroup[a].log.length > 0) {
                    //             $("#loadResult").append(batchIdentifierInfo).append("<br />").append(batchTable);
                    //         }
                    //     }
                    // }

                    $(".singleTable").each(function() {
                        var tar = parseFloat($(this).find("tbody tr:last-child td:eq(4) h5").attr("rawval"));
                        totalAllStock += tar;
                    });
                    $("#total_all").html(number_format(totalAllStock, 2, ",", "."));
                },
                error: function(resp) {
                    console.log(resp);
                }
            });
        }
    });
</script>