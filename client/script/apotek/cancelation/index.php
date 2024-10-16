<script type="text/javascript">
    $(function () {
        var currentUIDBatal = "";
        var tableResep = $("#table-resep").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Apotek",
                type: "POST",
                data: function(d) {
                    d.request = "get_resep_backend_v3";
                    d.request_type = "batal";
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    console.log(response);
                    var resepDataRaw = response.response_package.response_data;
                    var parsedData = [];
                    var IGD = [];

                    for(var resepKey in resepDataRaw) {
                        parsedData.push(resepDataRaw[resepKey]);
                    }


                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsTotal;

                    return parsedData;
                }
            },
            language: {
                search: "",
                searchPlaceholder: "No.RM/Nama Pasien"
            },
            autoWidth: false,
            "bInfo" : false,
            aaSorting: [[2, "asc"]],
            "columnDefs":[
                {"targets":0, "className":"dt-body-left"}
            ],
            "rowCallback": function ( row, data, index ) {
                if(data.departemen.uid === __POLI_IGD__) {
                    /*$("td", row).addClass("bg-danger").css({
                        "color": "#fff"
                    });*/
                }
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.created_at_parsed;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(row.departemen !== undefined && row.departemen !== null) {
                            if(row.departemen.uid === __POLI_INAP__) {
                                if(row.antrian.ns_detail !== undefined && row.antrian.ns_detail !== null) {
                                    return row.departemen.nama + "<br />" +
                                        "<span class=\"text-info\">" + row.antrian.ns_detail.kode_ns + "</span> - " + row.antrian.ns_detail.nama_ns;
                                } else {
                                    return "-";
                                }
                            } else {
                                return row.departemen.nama;
                            }
                        } else {
                            return "";
                        }
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return ("<h6 class=\"text-info\">" + row.pasien_info.no_rm + "</h6>") + ((row.pasien_info.panggilan_name !== undefined && row.pasien_info.panggilan_name !== null) ? row.pasien_info.panggilan_name.nama : "") + " " + row.pasien_info.nama;
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
                        var listerAlasan = "<div class=\"row\" style=\"padding-bottom: 50px\">";
                        var listAlasanRaw = row.cancelation;
                        for(var a in listAlasanRaw) {
                            listerAlasan += "<div class=\"col-lg-12\">" +
                                "<strong><b class=\"text-info\">" + listAlasanRaw[a].created_at + "</b> " + listAlasanRaw[a].oleh.nama + "</strong>" +
                                "<p>" + listAlasanRaw[a].alasan + "</p>" +
                                "</div>";
                        }
                        listerAlasan += "</div>";
                        return listerAlasan;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        if(__MY_PRIVILEGES__.response_data[0].uid === __UIDADMIN__) {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                "<button class=\"btn btn-success btn-sm btn-rewind-resep\" id=\"rewind_" + row.uid + "\">" +
                                "<span><i class=\"fa fa-ban\"></i> Aktifkan</span>" +
                                "</button>" +
                                "</div>";
                        } else {
                            return "<span class=\"wrap_content text-danger\"><i class=\"fa fa-times-circle\"></i> Tidak ada otoritas</span>";
                        }
                    }
                }
            ]
        });

        $("body").on("click", ".btn-rewind-resep", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentUIDBatal = id;
            $("#modal-batal").modal("show");
        });

        $("#btnProsesBatalResep").click(function() {
            var alasan = $("#keterangan_batal").val();
            if(alasan !== "") {
                Swal.fire({
                    title: "Aktifkan Resep?",
                    showDenyButton: true,
                    confirmButtonText: `Ya`,
                    denyButtonText: `Tidak`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: __HOSTAPI__ + "/Apotek",
                            async: false,
                            beforeSend: function (request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type: "POST",
                            data: {
                                request: "aktifkan_resep",
                                uid: currentUIDBatal,
                                alasan: alasan
                            },
                            success: function (response) {
                                if(response.response_package.response_result > 0) {
                                    Swal.fire(
                                        "Pembatalan Resep Berhasil!",
                                        "Resep dapat diverifikasi ulang",
                                        "success"
                                    ).then((result) => {
                                        tableResep.ajax.reload();
                                        $("#modal-batal").modal("hide");
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
            } else {
                Swal.fire(
                    "Aktifkan Resep",
                    "Alasan harus diisi",
                    "warning"
                ).then((result) => {
                    //
                });
            }
        });
    });
</script>

<div id="modal-batal" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Aktifkan Resep</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <strong>Keterangan Aktifkan Kembali Resep:</strong>
                <textarea id="keterangan_batal" class="form-control" style="min-height: 300px" placeholder="Wajib Isi (Required)"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnProsesBatalResep"><i class="fa fa-check"></i> Proses Aktifkan Resep</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-ban"></i> Close</button>
            </div>
        </div>
    </div>
</div>