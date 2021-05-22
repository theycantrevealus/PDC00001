<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function () {

        function getDateRange(target) {
            var rangeLaporan = $(target).val().split(" to ");
            if(rangeLaporan.length > 1) {
                return rangeLaporan;
            } else {
                return [rangeLaporan, rangeLaporan];
            }
        }

        function refresh_penjamin(target, selected = "") {
            var penjaminData = [];
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Penjamin/penjamin",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var data = response.response_package.response_data;
                    penjaminData = data;
                    $(target).find("option").remove();
                    for(var key in data) {
                        $(target).append("<option " + ((data[key].uid == selected) ? "selected=\"selected\"" : "") + " value=\"" + data[key].uid + "\">" + data[key].nama + "</option>");
                    }
                }
            });
            return penjaminData;
        }

        refresh_penjamin("#txt_penjamin");

        $("#txt_penjamin").select2().on("select2:select", function(e) {
            var data = e.params.data;
            var uid = data.id;

            tableLaporan.ajax.reload();
        });

        $("#range_laporan").change(function() {
            tableLaporan.ajax.reload();
        });

        var totalData = [];

        var tableLaporan = $("#tabel-laporan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                async:false,
                url: __HOSTAPI__ + "/Laporan",
                type: "POST",
                data: function(d) {
                    d.request = "keuangan";
                    d.from = getDateRange("#range_laporan")[0];
                    d.to = getDateRange("#range_laporan")[1];
                    d.penjamin = $("#txt_penjamin").val()
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {

                    console.log(response);

                    var returnedData = [];
                    var rawData = response.response_package.response_data;
                    if(response.response_package == undefined) {
                        rawData = [];
                    }

                    for(var keyData in rawData) {
                        if(rawData[keyData].payment !== null && rawData[keyData].payment !== undefined) {
                            returnedData.push(rawData[keyData]);
                        }
                    }



                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    totalData = returnedData;
                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parse;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nomor_invoice;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.payment !== null) ? row.payment.metode_bayar : "-";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ((row.pasien.panggilan_name !== null && row.pasien.panggilan_name !== undefined) ? row.pasien.panggilan_name.nama : "") + " " + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style\">" + number_format(row.total_after_discount, 2, '.', ',') + "<h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var terbayar = (row.payment !== null) ? row.payment.terbayar : 0;
                        return "<h6 class=\"number_style\">" + number_format(terbayar, 2, '.', ',') + "<h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var terbayar = (row.payment !== null) ? row.payment.terbayar : 0;
                        var sisa_bayar = row.total_after_discount - terbayar;
                        return "<h6 class=\"number_style\">" + number_format(sisa_bayar, 2, '.', ',') + "<h6>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h6 class=\"number_style\">" + (row.payment !== null) ? row.payment.nomor_kwitansi : "-" + "<h6>";
                    }
                }
            ]
        });


        $("#btnCetak").click(function () {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/laporan_keuangan.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __NAMA_SAYA__ : __MY_NAME__,
                    __JUDUL__ : "Invoice Listing",
                    __PERIODE_AWAL__ : getDateRange("#range_laporan")[0],
                    __PERIODE_AKHIR__ : getDateRange("#range_laporan")[1],
                    data: totalData

                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        importStyle: true,
                        header: null,
                        footer: null,
                        pageTitle: "Kwitansi",
                        afterPrint: function() {
                            $("#form-payment-detail").modal("hide");
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });
            return false;
        });
    });
</script>