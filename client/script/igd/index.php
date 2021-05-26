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
                url: __HOSTAPI__ + "/IGD",
                type: "POST",
                data: function(d) {
                    d.request = "get_igd";
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
                        for(var key in data) {
                            if(data[key].pasien !== null && data[key].pasien !== undefined) {}
                            returnedData.push(data[key]);
                        }
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
                searchPlaceholder: "Cari Nama Pasien / Nama Dokter / Ruangan"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.waktu_masuk_tanggal;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<b class=\"text-info\">" + row.pasien.no_rm + "</b><br />" + row.pasien.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.kamar.nama + "<br />" + row.bed.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.dokter.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.penjamin.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var jabatan_filter = (__MY_PRIVILEGES__.response_data[0].uid === __UIDDOKTER__) ? "dokter" : "perawat";

                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/igd/" + jabatan_filter + "/index/" + row.pasien.uid + "/" + row.kunjungan + "/" + row.penjamin.uid + "\" class=\"btn btn-sm btn-info\">" +
                            "<span><i class=\"fa fa-sign-out-alt\"></i>Proses</span>" +
                            "</a>" +
                            "<button class=\"btn btn-sm btn-success btn-pulangkan-pasien\" id=\"pulangkan_" + row.pasien.uid + "\">" +
                            "<span><i class=\"fa fa-check\"></i>Selesai</span>" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        var selectedUID = "";

        $("body").on("click", ".btn-pulangkan-pasien", function () {
            var id = $(this).attr("id").split("_");
            selectedUID = id[id.length - 1];

            $("#form-pulang").modal("show");
        });

        $("#btnSubmitPulang").click(function() {
            Swal.fire({
                title: "Rawat Inap",
                text: "Pulangkan pasien?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Inap",
                        type: "POST",
                        data: {
                            request: "pulangkan_pasien",
                            uid: selectedUID,
                            jenis: $("input[name=\"txt_jenis_pulang\"]:checked").val(),
                            keterangan: $("#txt_keterangan_pulang").val()
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function (response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Rawat Inap",
                                    "Pasien dipulangkan!",
                                    "success"
                                ).then((result) => {
                                    $("#form-pulang").modal("hide");
                                    listRI.ajax.reload();
                                });
                            } else {
                                console.log(response);
                            }
                        },
                        error: function (response) {
                            //
                        }
                    });
                }
            });
        });
    });
</script>




<div id="form-pulang" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Pemulangan Pasien</h5>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <h6>Jenis Pulang</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="P" checked/>
                                <label class="form-check-label">
                                    PAPS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="txt_jenis_pulang" value="D" />
                                <label class="form-check-label">
                                    Dokter
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <h6>Keterangan Pemulangan</h6>
                    <textarea style="min-height: 100px;" class="form-control" id="txt_keterangan_pulang"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitPulang">Proses</button>
            </div>
        </div>
    </div>
</div>