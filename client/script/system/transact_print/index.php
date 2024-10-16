<script type="text/javascript">
    $(function () {
        var MODE = "add";
        var tableTransact = $("#table-transact").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Setting",
                type: "POST",
                data: function(d) {
                    d.request = "get_transact_entry";
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

                    console.log(returnedData);

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Order"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.identifier;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button penjamin=\"" + row.uid_penjamin + "\" type=\"button\" id=\"order_lab_" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail-verif\" data-toggle='tooltip' title='Detail'>" +
                            "<span><i class=\"fa fa-search\"></i> Detail</span>" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });

        $("#btnTambahTransact").click(function () {
            MODE = "add";
            $("#modal-large-title").prepend("Add ");
            $("#modal-transact").modal("show");
        });
    });
</script>
<div id="modal-transact" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Transact Print</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-5">
                        <label for="txt_identifier">Identifier:</label>
                        <input type="text" class="form-control" id="txt_identifier" />
                    </div>
                    <div class="form-group col-md-7">
                        <label for="txt_target">Target:</label>
                        <input type="text" class="form-control" id="txt_target" />
                    </div>
                    <!--div class="col-md-6">
                        <label for="txt_module">Module:</label>
                        <div class="input-group mb-3">
                            <select class="form-control" id="txt_module" aria-describedby="basic-addon2"></select>
                            <div class="input-group-append" style="padding: 0 10px">
                                <button class="btn btn-info" type="button">
                                    <span>
                                        <i class="fa fa-plus-circle"></i> Tambah
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div-->
                    <div class="col-lg-11">
                        <h5>Modul Terkait</h5>
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-info" type="button" id="btnTambahModule">
                            <span>
                                <i class="fa fa-plus-circle"></i> Tambah
                            </span>
                        </button>
                    </div>
                    <div class="col-md-12">
                        <br />
                        <table class="table table-bordered largeDataType" id="table-pegawai-module">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="wrap_content">No</th>
                                    <th>Module</th>
                                    <th>Jabatan - Pegawai</th>
                                    <th class="wrap_content">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btn_verif_all">
                    <span>
                        <i class="fa fa-save"></i> Submit
                    </span>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>