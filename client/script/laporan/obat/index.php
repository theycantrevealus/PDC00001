<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function() {
        function getDateRange(target) {
            var rangeLaporan = $(target).val().split(" to ");
            if(rangeLaporan.length > 1) {
                return rangeLaporan;
            } else {
                return [rangeLaporan, rangeLaporan];
            }
        }

        function refresh_penjamin() {
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
                }
            });
            return penjaminData;
        }

        var penjaminData = refresh_penjamin();

        var penjaminColumn = [
            {
                "data" : null, render: function(data, type, row, meta) {
                    return row.autonum;
                }
            },
            {
                "data" : null, render: function(data, type, row, meta) {
                    return row.obat.nama;
                }
            }
        ];

        for(var pKey = 0; pKey < penjaminData.length; pKey++) {
            $("#tabel-laporan-obat-penjamin thead tr").append("<th style=\"width: 200px\">" + penjaminData[pKey].nama.toUpperCase() + "</th>");
            var target_name = penjaminData[pKey].uid;
            penjaminColumn.push({
                data : penjaminData[pKey].uid
            });
        }

        penjaminColumn.push({
            "data" : null, render: function(data, type, row, meta) {
                return "<span id=\"total_" + data.obat.uid + "\">" + row.total + "</span>";
            }
        });

        $("#tabel-laporan-obat-penjamin thead tr").append("<th style=\"width: 200px\">Total</th>");

        $("#range_laporan").change(function() {
            tablePenjamin.ajax.reload();
        });

        var returnedData = [];


        var tablePenjamin = $("#tabel-laporan-obat-penjamin").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                async:false,
                url: __HOSTAPI__ + "/Laporan",
                type: "POST",
                data: function(d) {
                    d.request = "obat_penjamin";
                    d.from = getDateRange("#range_laporan")[0];
                    d.to = getDateRange("#range_laporan")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {


                    returnedData = [];
                    /*console.clear();
                    console.log(response);*/



                    var rawData = response.response_package.response_data;
                    if(response.response_package == undefined) {
                        rawData = [];
                    }

                    for(var keyData in rawData) {
                        returnedData.push(rawData[keyData]);
                    }



                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Obat"
            },
            columns : penjaminColumn,
            drawCallback: function () {
                var api = this.api();

            }
        });

        $("#btnCetak").click(function () {

            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/laporan_pemakaian_obat.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __PC_CUSTOMER_GROUP__: __PC_CUSTOMER_GROUP__,
                    __NAMA_SAYA__ : __MY_NAME__,
                    __PENJAMIN__ : penjaminData,
                    __JUDUL__ : "Laporan Pemakaian Obat \n per Penjamin",
                    __PERIODE_AWAL__ : getDateRange("#range_laporan")[0],
                    __PERIODE_AKHIR__ : getDateRange("#range_laporan")[1],
                    data_laporan: returnedData

                },
                success: function (response) {
                    console.log(returnedData);
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    var win = window.open("", "Title", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=" + screen.width + ",height=" + screen.height + ",top=0,left=0");
                    win.document.body.innerHTML = $(containerItem).html();
                    /*$(containerItem).printThis({
                        header: null,
                        footer: null,
                        pageTitle: "Laporan Pemakaian Obat " + getDateRange("#range_laporan")[0] + " sampai " + getDateRange("#range_laporan")[1],
                        afterPrint: function() {
                            $("#form-payment-detail").modal("hide");
                        }
                    });*/
                },
                error: function (response) {
                    //
                }
            });
            return false;
        });
    });
</script>