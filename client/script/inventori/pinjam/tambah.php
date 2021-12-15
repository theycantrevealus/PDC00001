<script type="text/javascript">
    $(function() {

        function load_product(target, selectedData = "", appendData = true) {
			var selected = [];
			var productData;

			$(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Barang tidak ditemukan";
                    }
                },
                placeholder:"Cari Barang",
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
                                    id: item.uid,
                                    batch: item.batch,
                                    satuan_terkecil: item.satuan_terkecil,
                                    penjamin: item.penjamin
                                }
                            })
                        };
                    }
                }
            }).addClass("form-control item_pinjam").on("select2:select", function(e) {
                var data = e.params.data;
                
                // var penjaminListData = data.penjamin;
                // for(var penjaminKey in penjaminListData) {
                //     if(penjaminList.indexOf(penjaminListData[penjaminKey].penjamin.uid) < 0) {
                //         penjaminList.push(penjaminListData[penjaminKey].penjamin.uid);
                //     }
                // }
            });
		}

        function load_supplier(target, selected = "") {
			var kategoriData;
            $(target).select2({
                minimumInputLength: 2,
                "language": {
                    "noResults": function(){
                        return "Tidak ditemukan";
                    }
                },
                placeholder:"Nama Penerima Peminjaman",
                ajax: {
                    dataType: "json",
                    headers:{
                        "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                        "Content-Type" : "application/json",
                    },
                    url:__HOSTAPI__ + "/Supplier/get_supplier_select2",
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
            }).addClass("form-control item-amprah").on("select2:select", function(e) {
                var data = e.params.data;
            });
			return kategoriData;
		}

        function rebaseTable() {
            $("#table-detail-pinjam tbody tr").each(function(e) {
                var id = (e + 1);

                $(this).attr({
                    "id": "rowTable_" + id
                }).removeClass("last-row");

                $(this).find("td:eq(0) button").html("Hapus #" + id);
                $(this).find("td:eq(1) select").attr({
                    "id": "item_" + id
                });

                $(this).find("td:eq(2) select").attr({
                    "id": "batch_" + id
                });

                $(this).find("td:eq(3) input").attr({
                    "id": "qty_" + id
                });

                $(this).find("td:eq(4)").attr({
                    "id": "satuan_" + id
                });
            });
        }


        function autoItem(setter = {
            item: {
                uid: '',
                name: ''
            },
            batch: {
                uid: '',
                name: ''
            },
            qty: 0,
            satuan: ''
        }) {
            var newRow = document.createElement("TR");
            var newCellID = document.createElement("TD");
            var newCelllItem = document.createElement("TD");
            var newCelllBatch = document.createElement("TD");
            var newCelllQty = document.createElement("TD");
            var newCelllSatuan = document.createElement("TD");

            $(newCellID).addClass("wrap_content");

            var newButtonDelete = document.createElement("BUTTON");
            var newSelectpr = document.createElement("SELECT");
            var newBatch = document.createElement("SELECT");
            var newQty = document.createElement("INPUT");

            $("#table-detail-pinjam tbody").append(newRow);

            $(newButtonDelete).addClass("btn btn-danger").html("Hapus");

            $(newRow).append(newCellID);
            $(newRow).append(newCelllItem);
            $(newRow).append(newCelllBatch);
            $(newRow).append(newCelllQty);
            $(newRow).append(newCelllSatuan);

            $(newCellID).append(newButtonDelete);
            $(newCelllItem).append(newSelectpr);
            $(newCelllBatch).append(newBatch);
            $(newCelllQty).append(newQty);

            $(newBatch).select2();
            $(newQty).inputmask({
                alias: 'currency',
                rightAlign: true,
                placeholder: "0.00",
                prefix: "",
                autoGroup: false,
                digitsOptional: true
            }).addClass("form-control qty_check");

            load_product(newSelectpr);
            $(newSelectpr).addClass("form-control item");

            

            

            rebaseTable();
            $(newRow).addClass("last-row");
        }

        autoItem();

        load_supplier("#txt_supplier");

        $("#btnSubmitReturn").click(function() {
            var targetRS = $("#txt_supplier option:selected").val();
            var keterangan = $("#txt_tujuan").val();
            var itemList = [];

            $("#btnSubmitReturn").attr({
                "disabled": "disabled"
            }).addClass("btn-danger").removeClass("btn-success");

            $("#table-detail-pinjam tbody tr").each(function() {
                if(
                    !$(this).hasClass("last-row") &&
                    $(this).find("td:eq(1) select option:selected").val() !== "" && $(this).find("td:eq(1) select option:selected").val() !== undefined &&
                    $(this).find("td:eq(2) select option:selected").val() !== "" && $(this).find("td:eq(2) select option:selected").val() !== undefined &&
                    parseFloat($(this).find("td:eq(3) input").inputmask("unmaskedvalue")) > 0
                ) {
                    itemList.push({
                        item: $(this).find("td:eq(1) select option:selected").val(),
                        batch: $(this).find("td:eq(2) select option:selected").val(),
                        qty: parseFloat($(this).find("td:eq(3) input").inputmask("unmaskedvalue"))
                    });
                }
            });
            
            if(
                targetRS !== "" && targetRS !== undefined &&
                itemList.length > 0
            ) {
                Swal.fire({
                    title: 'Data sudah benar?',
                    showDenyButton: true,
                    confirmButtonText: `Ya`,
                    denyButtonText: `Belum`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            async: false,
                            url: __HOSTAPI__ + "/Inventori",
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: {
                                request: "pinjam_keluar",
                                tujuan: targetRS,
                                keterangan: keterangan,
                                item_list: itemList
                            },
                            success: function(resp) {
                                console.clear();
                                console.log(resp.response_package);
                                if(resp.response_package.response_result > 0) {
                                    Swal.fire(
                                        'Pengajuan Peminjaman Obat',
                                        'Pengajuan berhasil tersimpan',
                                        'success'
                                    ).then((result) => {
                                        location.href = __HOSTNAME__ + '/inventori/pinjam';
                                    });
                                } else {
                                    Swal.fire(
                                        'Pengajuan Peminjaman Obat',
                                        'Pengajuan gagal tersimpan',
                                        'error'
                                    ).then((result) => {
                                        
                                    });
                                }
                                $("#btnSubmitReturn").removeAttr("disabled").addClass("btn-success").removeClass("btn-danger");
                            },
                            error: function(resp) {
                                console.clear();
                                console.log(resp);
                            }
                        });
                    }
                });
            }
        });


        $("body").on("select2:select", ".item_pinjam", function(e) {
            var data = e.params.data;

            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            //console.clear();
            // console.log(data);

            var batchList = data.batch;

            $("#batch_" + id + " option").remove();
            for(var a in batchList) {
                if(batchList[a].gudang.uid === __GUDANG_UTAMA__) {
                    $("#batch_" + id).append("<option value=\"" + batchList[a].batch + "\">[" + batchList[a].expired + "] - " + batchList[a].kode.toUpperCase() + "</option>");
                }
            }

            $("#satuan_" + id).html(data.satuan_terkecil.nama);
            recheckAutoRow(id);
        });

        $("body").on("keyup", ".qty_check", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            recheckAutoRow(id);
        });

        function recheckAutoRow(id) {
            if(
                $("#rowTable_" + id).hasClass("last-row") &&
                $("#batch_" + id).val() !== undefined && $("#batch_" + id).val() !== "" &&
                $("#item_" + id + " option").length > 0 &&
                $("#item_" + id).val() !== undefined && $("#item_" + id).val() !== "" &&
                parseFloat($("#qty_" + id).inputmask("unmaskedvalue")) > 0
            ) {
                autoItem();
            }
        }
    });
</script>