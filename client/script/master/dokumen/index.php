<script type="text/javascript">
    $(function () {
        var tableDokumen = $("#table-dokumen").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Dokumen",
                type: "POST",
                data: function(d) {
                    d.request = "get_dokumen_back_end";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
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
                        return row.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/master/dokumen/edit/" + row.uid + "\" class=\"btn btn-info btn-sm\">" +
                            "<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
                            "</a> " +
                            "<button id=\"gudang_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-gudang\">" +
                            "<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });
    });
</script>