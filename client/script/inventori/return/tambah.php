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
                                supplier: item.supplier,
                                nama_supplier: item. nama_supplier,
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

                    $("#nomor_po").html(data.text);
                    $("#nama_supplier").html(data.nama_supplier);
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
                                var autonum = parseInt(a) + 1;
                                console.log(detailData[a]);
                                var newRow = document.createElement("TR");
                                var newCellNo = document.createElement("TD");
                                var newCellItem = document.createElement("TD");
                                var newCellQtyOrder = document.createElement("TD");
                                var newCellQty = document.createElement("TD");
                                var newCellSatuan = document.createElement("TD");

                                var newQty = document.createElement("INPUT");

                                $(newRow).append(newCellNo);
                                $(newRow).append(newCellItem);
                                $(newRow).append(newCellItem);
                                $(newRow).append(newCellQtyOrder);
                                $(newRow).append(newCellQty);
                                $(newRow).append(newCellSatuan);

                                $(newCellQtyOrder).html("<h5 class=\"number_style\">" + number_format(detailData[a].qty, 2, ".", ",") + "</h5>");

                                $(newCellQty).append(newQty);
                                $(newQty).inputmask({
                                    alias: 'currency', min: 0, max: parseFloat(detailData[a].qty), rightAlign: true, placeholder: "0,00", prefix: "", autoGroup: false, digitsOptional: true
                                }).addClass("form-control qty");

                                $(newCellNo).html("<h5 class=\"autonum\">" + autonum + "</h5>");
                                $(newCellItem).html(detailData[a].barang_detail.nama).attr({
                                    "uid": detailData[a].barang_detail.uid
                                });

                                $(newCellSatuan).html(detailData[a].barang_detail.satuan_terkecil_info.nama);

                                $("#table-detail-return tbody").append(newRow);

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
                var qty = $(this).find("td:eq(3) input").inputmask("unmaskedvalue");

                if(qty > 0) {
                    items.push({
                        item: $(this).find("td:eq(1)").attr("uid"),
                        qty: qty
                    });
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
                        //
                    }
                });
            }
        });


        function loading_data() {
            $("#table-detail-return tbody").html("");
            $("#table-detail-return tbody").append("<tr><td colspan='4' class='text-center'>Loading... </td></tr>");
        }
    });

</script>