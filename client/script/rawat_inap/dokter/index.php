<script type="text/javascript">
    $(function () {
        var listRI = $("#table-antrian-rawat-jalan").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inap",
                type: "POST",
                data: function(d) {
                    d.request = "get_rawat_inap";
                    d.division = "doctor";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        var data = response.response_package.response_data;
                        var autonum = 1;
                        for(var key in data) {
                            data[key].autonum = autonum;
                            returnedData.push(data[key]);
                            autonum++;
                            // if(
                            //     data[key].pasien !== null && data[key].pasien !== undefined &&
                            //     //data[key].dokter.uid === __ME__ &&
                            //     data[key].nurse_station !== null
                            // ) {
                            //     data[key].autonum = autonum;
                            //     returnedData.push(data[key]);
                            //     autonum++;
                            // }
                        }
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nama Pasien / Nama Dokter / Ruangan"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"uid_" + row.uid + "\" keterangan=\"" + row.keterangan + "\">" + row.autonum + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\">" + row.waktu_masuk_tanggal + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b kunjungan=\"" + row.kunjungan + "\" data=\"" + row.pasien.uid + "\" id=\"pasien_" + row.uid + "\" class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.kamar !== null) ? "<span bed=\"" + row.bed.uid + "\" kamar=\"" + row.kamar.uid + "\" id=\"kamar_" + row.uid + "\">" + row.kamar.nama + "</span><br />" + row.bed.nama : "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"dokter_" + row.uid + "\" data=\"" + row.dokter.uid + "\">" + row.dokter.nama + "</span>"
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"penjamin_" + row.uid + "\" data=\"" + row.penjamin.uid + "\">" + row.penjamin.nama + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/rawat_inap/dokter/asesmen-detail/" + row.pasien.uid + "/" + row.kunjungan + "/" + row.penjamin.uid + "/" + row.uid + "\" class=\"btn btn-sm btn-info btnProsesInap\" id=\"btn_proses_" + row.uid + "\">" +
                            "<span><i class=\"fa fa-sign-out-alt\"></i> Proses</span>" +
                            "<a>" +
                            "</div>";
                    }
                }
            ]
        });
    });
</script>