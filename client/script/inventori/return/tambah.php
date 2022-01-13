<script type="text/javascript">
    $(function () {
        var targetPO = "", targetSupplier = "";
        $("#txt_po").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Barang tidak ditemukan";
                }
            },
            placeholder:"Cari Nomor Transaksi",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/PO/select2",
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
                                text: item.nomor_po,
                                id: item.uid,
                                uid_supplier: item.uid_supplier,
                                sup_type: item.supplier_type,
                                supplier: item.supplier,
                                nama_supplier: item.nama_supplier,
                                created_at: item.created_at_parsed
                            }
                        })
                    };
                }
            }
        }).addClass("form-control item-amprah").on("select2:select", function(e) {
            Swal.fire({
                title: "Memuat data PO",
                text: "Data yang sedang diinput akan hilang. Apakah Anda ingin mengabaikannya?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    var data = e.params.data;
                    targetPO = data.id;
                    targetSupplier = data.supplier;

                    $("#nomor_po").html(data.text).attr({
                        "uid": data.id
                    });
                    $("#nama_supplier").html(data.nama_supplier + " <b>[" + ((data.sup_type === 'B') ? "Rumah Sakit" : "Supplier Biasa") + "]</b>").attr({
                        "uid": data.uid_supplier
                    });
                    $("#tanggal_po").html(data.created_at);
                    loading_data();

                    //Get Detail
                    $.ajax({
                        url:__HOSTAPI__ + "/PO/detail/" + data.id,
                        async:false,
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"GET",
                        success:function(response) {
                            $("#table-detail-return tbody").html("");
                            var detailData = response.response_package.response_data;

                            for(var a in detailData) {
                                $("#no_invoice").html(detailData[a].do.no_invoice + "/" + detailData[a].do.no_do);
                                var autonum = parseInt(a) + 1;

                                var newRow, newCellNo, newCellItem, newCellQtyOrder, newCellQty, newCellSatuan, newQty;
                                var availBatch = detailData[a].batch_avail;
                                for(var b in availBatch) {
                                    if(parseInt(b) === 0) {
                                        newRow = document.createElement("TR");
                                        newCellNo = document.createElement("TD");
                                        newCellItem = document.createElement("TD");
                                        newCellQtyOrder = document.createElement("TD");
                                        newCellQty = document.createElement("TD");
                                        newCellSatuan = document.createElement("TD");

                                        newQty = document.createElement("INPUT");
                                        $(newRow).addClass("first-batch");
                                        $(newRow).append(newCellNo);
                                        $(newRow).append(newCellItem);
                                        $(newRow).append(newCellItem);
                                        $(newRow).append(newCellQtyOrder);
                                        $(newRow).append(newCellQty);
                                        $(newRow).append(newCellSatuan);

                                        $(newCellNo).attr({
                                            "rowspan": availBatch.length
                                        });

                                        //$(newCellQtyOrder).html("<h5 class=\"number_style\">" + number_format(detailData[a].qty, 2, ".", ",") + "</h5>");
                                        $(newCellQtyOrder).html("<strong class=\"wrap_content\">" + availBatch[b].kode + " (Max : " + number_format(parseFloat(availBatch[b].stok_terkini), 2, ",", ",") + ")</strong>");

                                        $(newCellQty).append(newQty);
                                        $(newQty).inputmask({
                                            alias: 'currency', min: 0, max: parseFloat(availBatch[b].stok_terkini), rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
                                        }).addClass("form-control qty").attr({
                                            "uid": detailData[a].barang_detail.uid,
                                            "batch": availBatch[b].batch
                                        });

                                        $(newCellNo).html("<h5 class=\"autonum\">" + autonum + "</h5>");
                                        $(newCellItem).html(detailData[a].barang_detail.nama).attr({
                                            "rowspan": availBatch.length
                                        });

                                        $(newCellSatuan).html(detailData[a].barang_detail.satuan_terkecil_info.nama);

                                        $("#table-detail-return tbody").append(newRow);
                                    } else {
                                        newRow = document.createElement("TR");
                                        newCellQtyOrder = document.createElement("TD");
                                        newCellQty = document.createElement("TD");
                                        newCellSatuan = document.createElement("TD");

                                        newQty = document.createElement("INPUT");

                                        $(newRow).append(newCellQtyOrder);
                                        $(newRow).append(newCellQty);
                                        $(newRow).append(newCellSatuan);

                                        //$(newCellQtyOrder).html("<h5 class=\"number_style\">" + number_format(detailData[a].qty, 2, ".", ",") + "</h5>");
                                        $(newCellQtyOrder).html("<strong class=\"wrap_content\">" + availBatch[b].kode + " (Max : " + number_format(parseFloat(availBatch[b].stok_terkini), 2, ",", ",") + ")</strong>");

                                        $(newCellQty).append(newQty);
                                        $(newQty).inputmask({
                                            alias: 'currency', min: 0, max: parseFloat(availBatch[b].stok_terkini), rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
                                        }).addClass("form-control qty").attr({
                                            "uid": detailData[a].barang_detail.uid,
                                            "batch": availBatch[b].batch
                                        });

                                        $(newCellNo).html("<h5 class=\"autonum\">" + autonum + "</h5>");
                                        $(newCellItem).html(detailData[a].barang_detail.nama);

                                        $(newCellSatuan).html(detailData[a].barang_detail.satuan_terkecil_info.nama);

                                        $("#table-detail-return tbody").append(newRow);
                                    }
                                }

                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("#btnSubmitReturn").click(function () {
            var items = [];
            $("#table-detail-return tbody tr").each(function () {
                var qty = 0;
                var batch = "";
                if($(this).hasClass("first-batch")) {
                    qty = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");
                    batch = $(this).find("td:eq(3) input").attr("batch");

                    if(qty > 0) {
                        items.push({
                            item: $(this).find("td:eq(3) input").attr("uid"),
                            batch: batch,
                            qty: qty
                        });
                    }
                } else {
                    qty = $(this).find("td:eq(1) input").inputmask("unmaskedvalue");
                    batch = $(this).find("td:eq(1) input").attr("batch");

                    if(qty > 0) {
                        items.push({
                            item: $(this).find("td:eq(1) input").attr("uid"),
                            batch: batch,
                            qty: qty
                        });
                    }
                }
            });

            if(items.length > 0 && targetPO !== "") {
                Swal.fire({
                    title: "Pemgembalian Barang",
                    text: "Transaksi ini akan mengurangi stok gudang utama. Pastikan data sudah benar!",
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Belum",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: __HOSTAPI__ + "/Inventori",
                            async: false,
                            data: {
                                request: "retur_po",
                                supplier: $("#nama_supplier").attr("uid"),
                                po: $("#nomor_po").attr("uid"),
                                keterangan: $("#txt_keterangan").val(),
                                items: items
                            },
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            success: function (response) {
                                var data = response.response_package.response_result;
                                if(data > 0) {
                                    Swal.fire(
                                        "Inventori Retur",
                                        "Berhasil return. Silahkan cek kartu stok untuk memastikan",
                                        "success"
                                    ).then((result) => {
                                        location.href = __HOSTNAME__ + "/inventori/return";
                                        /*console.clear();
                                        console.log(response);*/
                                    });
                                } else {
                                    Swal.fire(
                                        "Inventori Retur",
                                        "Gagal proses data",
                                        "error"
                                    ).then((result) => {
                                        console.log(response);
                                    });
                                }
                            },
                            error: function (response) {
                                console.clear();
                                console.log(response);
                            }
                        });
                    }
                });
            } else {
                if(items.length >= 0) {
                    Swal.fire(
                        "Inventori Retur",
                        "Tidak ada barang yang dapat diretur",
                        "warning"
                    ).then((result) => {
                        //
                    });
                } else if(targetPO === "") {
                    Swal.fire(
                        "Inventori Retur",
                        "Pilih transaksi pemasukan obat sebelumnya",
                        "warning"
                    ).then((result) => {
                        //
                    });
                }
            }
        });


        function loading_data() {
            $("#table-detail-return tbody").html("");
            $("#table-detail-return tbody").append("<tr><td colspan='4' class='text-center'>Loading... </td></tr>");
        }
    });

</script>