<script type="text/javascript">
    $(function () {
        var currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
        reCheckStatus(currentStatus);
        protocolLib = {
            opname_warehouse: function(protocols, type, parameter, sender, receiver, time) {
                if(sender !== __ME__) {
                    notification (type, parameter, 3000, "opname_notifier");
                }
                currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
                reCheckStatus(currentStatus);
            },
            opname_warehouse_finish: function(protocols, type, parameter, sender, receiver, time) {
                if(sender !== __ME__) {
                    notification (type, parameter, 3000, "opname_notifier");
                }
                currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
                reCheckStatus(currentStatus);
            },
        };


        function reCheckStatus(currentStatus) {
            if(currentStatus === "A") {
                $("#allow_transact_opname button").removeAttr("disabled");
            } else {
                $("#warning_allow_transact_opname").append(" Harap <a href=\"" + __HOSTNAME__ + "/inventori/stok/penyesuaian\"><i class=\"fa fa-link\"></i> selesaikan opname</a> dahulu agar dapat melanjutkan proses transaksi");
                $("#allow_transact_opname button").attr({
                    "disabled": "disabled"
                });
            }
        }
        var tempTransact = $("#table-temp").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "get_temp_transact";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var rawData = [];

                    if(response === undefined || response.response_package === undefined) {
                        rawData = [];
                    } else {
                        rawData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return rawData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Barang"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"text-right " + ((__UNIT__.gudang === row.gudang_asal.uid) ? "text-info" : "") + "\">" + row.gudang_asal.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.gudang_tujuan !== undefined && row.gudang_tujuan !== null) {
                            return "<span style=\"display: block\" class=\"" + ((__UNIT__.gudang === row.gudang_tujuan.uid) ? "text-info" : "") + "\">" + row.gudang_tujuan.nama + "</span>";
                        } else {
                            return "-";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"number_style\">" + number_format(row.qty, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.item.satuan_terkecil_info.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var solution = "";
                        if(row.gudang_tujuan !== undefined && row.gudang_tujuan !== null) {
                            if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_tujuan.uid === __UNIT__.gudang) {
                                if(row.gudang_asal.uid === __GUDANG_UTAMA__) {
                                    solution = "amprah";
                                } else {
                                    solution = "mutasi";
                                }
                            } else if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_asal.uid === __UNIT__.gudang && row.gudang_tujuan.uid === NULL) {
                                solution = "general";
                            } else {
                                solution = "undefined";
                            }
                        } else {
                            if((row.transact_table === "resep" || row.transact_table === "racikan") && row.gudang_asal.uid === __UNIT__.gudang) {
                                solution = "general";
                            } else {
                                solution = "undefined";
                            }
                        }
                        return "<span class=\"badge badge-custom-caption badge-outline-info\">" + solution.toUpperCase() + "</span>";
                    }
                }
            ]
        });
    });
</script>