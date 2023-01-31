<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function () {
        var totalData = [];
        function getDateRange(target) {
            var rangeLaporan = $(target).val().split(" to ");
            if(rangeLaporan.length > 1) {
                return rangeLaporan;
            } else {
                return [rangeLaporan, rangeLaporan];
            }
        }

        $("#range_laporan").change(function() {
            tableLaporan.ajax.reload();
        });

        

        var tableLaporan = $("#tabel-laporan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[10, 50, -1], [10, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                async:false,
                url: __HOSTAPI__ + "/Laporan",
                type: "POST",
                data: function(d) {
                    d.request = "kunjungan_rawat_jalan";
                    d.from = getDateRange("#range_laporan")[0];
                    d.to = getDateRange("#range_laporan")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                
                    var returnedData = [];
                    var returnedData = response.response_package.response_data;

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
                        return row.waktu_masuk;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.waktu_keluar;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ((row.pasien.panggilan_name !== null && row.pasien.panggilan_name !== undefined) ? row.pasien.panggilan_name.nama : "") + " " + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pasien.jenkel_detail.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pasien.alamat;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.nama_departemen;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pasien.no_rm;
                    }
                }
            ]
        });


        // console.log(getDateRange("#range_laporan")[0]);

        $("#btnCetak").click(function () {
            var t = $(this);
            t.prop("disabled", true).text("proses...");
    
            $.ajax({
                async:true,
                url: __HOSTAPI__ + "/Laporan",
                type: "POST",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                data: {
                    request : "print_kunjungan_rawat_jalan",
                    from : getDateRange("#range_laporan")[0],
                    to : getDateRange("#range_laporan")[1]
                },
                success: function(response) {
                    t.prop("disabled", false).html("<i class=\"fa fa-print\"></i> Cetak");
                    var data =  response.response_package.response_data
                    $.ajax({
                        async: false,
                        url: __HOST__ + "miscellaneous/print_template/laporan_kunjungan_rawat_jalan.php",
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
                            __JUDUL__ : "Laporan Kunjungan Rawat Jalan",
                            __PERIODE_AWAL__ : getDateRange("#range_laporan")[0],
                            __PERIODE_AKHIR__ : getDateRange("#range_laporan")[1],
                            data: data

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
                                pageTitle: "Laporan Kunjungan Rawat Jalan",
                                afterPrint: function() {
                                    $("#form-payment-detail").modal("hide");
                                }
                            });
                        },
                        error: function (response) {
                            //
                        }
                    });
                },
                error: function(err){
                    console.log("error");
                }

            });

        });


        // $("#btnCetak").click(function () {
            

        //     $.ajax({
        //         async: false,
        //         url: __HOST__ + "miscellaneous/print_template/laporan_kunjungan_rawat_jalan.php",
        //         beforeSend: function (request) {
        //             request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
        //         },
        //         type: "POST",
        //         data: {
        //             __HOSTNAME__: __HOSTNAME__,
        //             __PC_CUSTOMER__: __PC_CUSTOMER__,
        //             __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
        //             __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
        //             __NAMA_SAYA__ : __MY_NAME__,
        //             __JUDUL__ : "Laporan Kunjungan Rawat Jalan",
        //             __PERIODE_AWAL__ : getDateRange("#range_laporan")[0],
        //             __PERIODE_AKHIR__ : getDateRange("#range_laporan")[1],
        //             data: totalData

        //         },
        //         success: function (response) {
        //             console.log(response);
        //             var containerItem = document.createElement("DIV");
        //             $(containerItem).html(response);
        //             $(containerItem).printThis({
        //                 importCSS: true,
        //                 base: false,
        //                 importStyle: true,
        //                 header: null,
        //                 footer: null,
        //                 pageTitle: "Laporan Kunjungan Rawat Jalan",
        //                 afterPrint: function() {
        //                     $("#form-payment-detail").modal("hide");
        //                 }
        //             });
        //         },
        //         error: function (response) {
        //             //
        //         }
        //     });
        //     return false;
        // });
    });
</script>