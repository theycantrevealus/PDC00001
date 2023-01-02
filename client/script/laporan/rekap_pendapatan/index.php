<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
    $(function () {

        for(var az = parseInt(<?php echo json_encode(date('Y')) ?>); az >= 2010 ; az--) {
            $("#periode-laporan").append("<optgroup label=\"-----------------------" + az + "\">");
            
            for(var agg in monthName) {
                $("#periode-laporan").append("<option " + (((az) === parseInt(<?php echo json_encode(date('Y')) ?>) && (parseInt(agg) + 1) === parseInt(<?php echo json_encode(date('m')) ?>)) ? 'selected=\"selected\"' : '') + " value=\"" + az + "_" + agg + "\">" + monthName[agg] + " " + az + "</option>");
            }

            $("#periode-laporan").append("</optgroup>");
        }

        function refresh_template(target, bulan, tahun, selected = "") {
            $("#tabel-laporan tbody tr").remove();
            var data = [];
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Laporan/template_rekap/" + bulan + "/" + tahun,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var tableBuilder = {};
                    var raw = response.response_package.response_data;
                    for(var a in raw) {
                        var identifyKat = raw[a].kategori.toLowerCase().replaceAll(' ', '_').replaceAll('&_', '').replaceAll('/', '');
                        var identifySub = raw[a].subkategori.toLowerCase().replaceAll(' ', '_').replaceAll('&_', '').replaceAll('/', '');

                        if(!tableBuilder[identifyKat]) {
                            tableBuilder[identifyKat] = {
                                data: {},
                                caption: raw[a].kategori
                            };
                        }

                        if(!tableBuilder[identifyKat].data[identifySub]) {
                            tableBuilder[identifyKat].data[identifySub] = {
                                data: raw[a].total,
                                caption: raw[a].subkategori
                            };
                        }

                    }

                    var catCounter = 1;

                    for(var b in tableBuilder) {
                        var newCat = document.createElement('TR');
                        var catCell1 = document.createElement('TD');
                        var catCell2 = document.createElement('TD');
                        var catCell3 = document.createElement('TD');
                        var catCell4 = document.createElement('TD');

                        $(newCat).append(catCell1);
                        $(newCat).append(catCell2);
                        // $(newCat).append(catCell3);
                        // $(newCat).append(catCell4);

                        $(catCell1).attr({
                            "rowspan": Object.keys(tableBuilder[b].data).length + 1
                        }).html(catCounter);

                        $(catCell2).attr({
                            "rowspan": Object.keys(tableBuilder[b].data).length + 1
                        }).html(tableBuilder[b].caption);

                        $("#tabel-laporan tbody").append(newCat);

                        var subDataSet = tableBuilder[b].data;
                        for(var c in subDataSet) {
                            var newSub = document.createElement('TR');
                            var newCellSub1 = document.createElement('TD');
                            var newCellSub2 = document.createElement('TD');
                            var newCellSub3 = document.createElement('TD');
                            var newCellSub4 = document.createElement('TD');

                            $(newSub).append(newCellSub1);
                            $(newSub).append(newCellSub3);
                            $(newSub).append(newCellSub2);
                            

                            $(newCellSub1).html(subDataSet[c].caption);
                            $(newCellSub2).addClass("number_style").html("<h6>" + number_format(subDataSet[c].data, 2, ',','.') + "</h6>");
                            $(newCellSub3).html('Rp');
                            
                            $("#tabel-laporan tbody").append(newSub);
                        }
                        catCounter++;
                    }
                }
            });
            return data;
        }

        refresh_template("#periode-laporan", '12', '2022');

        $("#periode-laporan").select2().on("select2:select", function(e) {
            var data = e.params.data;
            var uid = data.id.split("_");
            
            refresh_template("#periode-laporan", parseInt(uid[1]) + 1, parseInt(uid[0]));

            // tableLaporan.ajax.reload();
        });

        var totalData = [];


        $("#btnCetak").click(function () {
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/laporan_rekap_keuangan.php",
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
                    __PERIODE__ : $("#periode-laporan option:selected").text(),
                    data: $("#tabel-laporan").html()

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