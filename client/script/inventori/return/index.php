<script type="text/javascript">
    $(function () {
        var returnTable = $("#table-return").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax": {
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function (d) {
                    d.request = "get_return_entry";
                    /*d.from = getDateRange("#range_amprah")[0];
                    d.to = getDateRange("#range_amprah")[1];*/
                },
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc: function (response) {
                    var dataSet = response.response_package.response_data;
                    if (dataSet === undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    console.log(dataSet);
                    return dataSet;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Kode Retur"
            },
            "columns": [
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.kode;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.supplier.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return row.pegawai.nama;
                    }
                },
                {
                    "data": null, render: function (data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button class=\"btn btn-info btnView\" id=\"view_" + row.uid + "\"><span><i class=\"fa fa-eye\"></i> Detail</span></button>" +
                            "</div>";
                    }
                }
            ]
        });

        $("body").on("click", ".btnView", function () {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];

            $("#form-detail-retur").modal("show");
        });
    });
</script>

<div id="form-detail-retur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informasi Retur</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data-print">
                <table class="form-mode table">
                    <tr>
                        <td>Kode</td>
                        <td class="wrap_content">:</td>
                        <td id="retur_kode_retur"></td>

                        <td>Tanggal Retur</td>
                        <td class="wrap_content">:</td>
                        <td id="retur_tanggal_retur"></td>

                        <td>Pemasok</td>
                        <td class="wrap_content">:</td>
                        <td id="retur_pemasok"></td>
                    </tr>
                    <tr>
                        <td>Diproses Oleh</td>
                        <td>:</td>
                        <td id="retur_pegawai"></td>
                    </tr>
                </table>
                <table class="table table-bordered largeDataType" id="detail_retur">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th>Barang</th>
                        <th class="wrap_content">Batch</th>
                        <th class="wrap_content">Satuan</th>
                        <th class="wrap_content">Qty</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <b>Keterangan:</b><br />
                <span id="keterangan-all"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btn_cetak_retur">Cetak</button>
            </div>
        </div>
    </div>
</div>