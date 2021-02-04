<script type="text/javascript">
    $(function () {
        $("#range_sep").change(function() {
            SEPList.ajax.reload();
        });

        $("#jenis_pelayanan").select2().on("select2:select", function(e) {
            SEPList.ajax.reload();
        });

        var SEPList = $("#table-sep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/BPJS",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function (d) {
                    d.request = "get_history_sep_local";
                    d.dari = getDateRange("#range_sep")[0];
                    d.sampai = getDateRange("#range_sep")[1];
                    d.pelayanan_jenis = $("#jenis_pelayanan").val();
                },
                dataSrc: function (response) {
                    console.clear();
                    console.log(response);
                    var data = response.response_package.response_data;
                    if (data === undefined) {
                        data = [];
                    }
                    return data;
                }
            },
            autoWidth: false,
            "bInfo": false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs": [{
                "targets": 0,
                "className": "dt-body-left"
            }],
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.sep_no;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<b class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + ((row.pasien.panggilan_name !== undefined) ? row.pasien.panggilan_name.nama : "") + " " + row.pasien.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "";
                    }
                }
            ]
        });
    });
</script>