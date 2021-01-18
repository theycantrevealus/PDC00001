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

        $("#range_laporan").change(function() {
            tableLaporan.ajax.reload();
        });

        var tableLaporan = $("#tabel-laporan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
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
                    console.log(response);
                    var returnedData = [];
                    if(returnedData == undefined || returnedData.response_package == undefined) {
                        returnedData = [];
                    }
                    for(var InvKeyData in response.response_package.response_data) {
                        returnedData.push(response.response_package.response_data[InvKeyData]);
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
                searchPlaceholder: "Cari Nomor Invoice"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"white-space: pre\">" + row.nomor_invoice + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(
                            row.pasien.panggilan_name !== undefined &&
                            row.pasien.panggilan_name !== null
                        ) {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.panggilan_name.nama + " " + row.pasien.nama + "</b>";
                        } else {
                            return row.pasien.no_rm + "<br /><b>" + row.pasien.nama + "</b>";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.antrian_kunjungan.poli.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        //return row.antrian_kunjungan.pegawai.nama + " di <b>" + row.antrian_kunjungan.loket.nama_loket + "</b>";
                        return row.antrian_kunjungan.pegawai.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span style=\"display: block\" class=\"text-right\">" + number_format(row.total_after_discount, 2, ".", ",") + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return 	"<button class=\"btn btn-info btn-sm btnDetail\" id=\"invoice_" + row.uid + "\" pasien=\"" + row.pasien.uid + "\" penjamin=\"" + row.antrian_kunjungan.penjamin + "\" poli=\"" + row.antrian_kunjungan.poli.uid + "\" kunjungan=\"" + row.kunjungan + "\"><i class=\"fa fa-eye\"></i></button>";
                    }
                }
            ]
        });


        $("#btnCetak").click(function () {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/laporan_kunjungan_rawat_jalan.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __NAMA_SAYA__ : __MY_NAME__,

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