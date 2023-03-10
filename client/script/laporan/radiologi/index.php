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
                    d.request = "radiologi";
                    d.from = getDateRange("#range_laporan")[0];
                    d.to = getDateRange("#range_laporan")[1];
                    d.mode = "history";
                    d.status = 'D';
                    // d.penjamin = $("#txt_penjamin").val()
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
                        returnedData.push(rawData[keyData]);
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
                searchPlaceholder: "Cari Nama Pasien"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"tanggal_labor_" + row.uid + "\">" + row["waktu_order"] + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"kode_" + row.uid + "\">" + row["no_order"] + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["no_rm"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["pasien"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["departemen"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["dokter"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nama_tindakan"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nama_mitra"];
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row["nama_penjamin"];
                    }
                },
                // {
                //     "data" : null, render: function(data, type, row, meta) {
                //         return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                //             "<a href=\"" + __HOSTNAME__ + "/laboratorium/view/" + row['uid'] + "/\" class=\"btn btn-info btn-sm\">" +
                //             "<i class=\"fa fa-eye\"></i> Detail" +
                //             "</a>" +
                //             "<button class=\"btn btn-purple btn-sm btnCetak\" id=\"lab_" + row.uid + "\">" +
                //             "<i class=\"fa fa-print\"></i> Cetak" +
                //             "</button>" +
                //             "</div>";
                //     }
                // }
            ]
        });


        $("#btnCetak").click(function () {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/laporan_radiologi.php",
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
                    __JUDUL__ : "Laporan Radiologi",
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