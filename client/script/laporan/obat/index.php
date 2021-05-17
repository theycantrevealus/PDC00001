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
                "data" : null, render: function(data, type, row, meta) {
                    return "<span id=\"jumlah_penjamin_" + row.obat.uid + "_" + target_name + "\"></span>";
                }
            });
        }

        penjaminColumn.push({
            "data" : null, render: function(data, type, row, meta) {
                return "";
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
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
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

                    console.clear();
                    console.log(response);


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
                searchPlaceholder: "Cari Nomor Invoice"
            },
            columns : penjaminColumn,
            drawCallback: function () {
                var api = this.api();
                for(var a in returnedData) {
                    for(var b in returnedData[a].penjamin) {
                        console.log("#jumlah_penjamin_" + returnedData[a].obat.uid + "_" + b);
                        $("#jumlah_penjamin_" + returnedData[a].obat.uid + "_" + b).html(returnedData[a].penjamin[b]);
                    }
                }
            }
        });
    });
</script>