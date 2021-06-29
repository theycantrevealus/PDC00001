<script type="text/javascript">
    $(function () {
        var tableAkun = $("#table-akun").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/JKN",
                type: "POST",
                headers: {
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                data: function(d) {
                    d.request = "get_akun";
                },
                dataSrc:function(response) {
                    console.log(response);
                    var returnedData = [];
                    if(response == undefined || response.response_data == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_data;
                    }

                    response.draw = parseInt(response.response_draw);

                    return returnedData;
                }
            },
            autoWidth: false,
            "bInfo" : false,
            lengthMenu: [[-1], ["All"]],
            aaSorting: [[0, "asc"]],
            "columnDefs":[{
                "targets":0,
                "className":"dt-body-left"
            }],
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<span id=\"username_" + row.uid + "\">" + row.username + "</span>";
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.password_text;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<button id=\"user_edit_" + row['uid'] + "\" class=\"btn btn-info btn-sm btn-gen-password\">" +
                            "<i class=\"fa fa-sync\"></i> Generate Password" +
                            "</button>" +
                            "<button id=\"user_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-user\">" +
                            "<i class=\"fa fa-trash\"></i> Hapus" +
                            "</button>" +
                            "</div>";
                    }
                }
            ]
        });

        var modeUser = "tambah";


        $("#btnTambahAkun").click(function () {
            modeUser = "tambah";
            $("#form-user").modal("show");
        });

        $("body").on("click", ".btn-delete-user", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "JKN Mobile",
                text: "Hapus akses user [" + $("#username_" + uid).html() + "] ?",
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/JKN",
                        data: {
                            request: "hapus_user",
                            uid: uid
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function (response) {
                            Swal.fire(
                                "JKN Mobile",
                                response.response_message,
                                (response.response_result > 0) ? "success" : "error"
                            ).then((result) => {
                                tableAkun.ajax.reload();
                            });
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                }
            });
        });


        $("body").on("click", ".btn-gen-password", function () {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: 'JKN Mobile',
                text: 'Generate password baru untuk user ini?',
                showDenyButton: true,
                //showCancelButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Tidak`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        async: false,
                        url: __HOSTAPI__ + "/JKN",
                        data: {
                            request: "generate_password",
                            uid: uid
                        },
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        success: function (response) {
                            Swal.fire(
                                "JKN Mobile",
                                response.response_message,
                                (response.response_result > 0) ? "success" : "error"
                            ).then((result) => {
                                tableAkun.ajax.reload();
                            });
                        },
                        error: function (response) {
                            console.log(response);
                        }
                    });
                }
            });
        });

        $("#btnSubmitUser").click(function () {
            var username = $("#txt_username").val();
            if(modeUser == "tambah") {
                form_data = {
                    "request": "tambah_user",
                    "username": username
                };
            } else {
                form_data = {
                    "request": "edit_user",
                    "uid": selectedUIDUser,
                    "username": username
                };
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/JKN",
                data: form_data,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                success: function(response){
                    Swal.fire(
                        "JKN Mobile",
                        response.response_message,
                        (response.response_result > 0) ? "success" : "error"
                    ).then((result) => {
                        $("#txt_username").val("");
                        $("#form-user").modal("hide");
                        tableAkun.ajax.reload();
                    });

                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
    });
</script>

<div id="form-user" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Data User JKN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label for="txt_username">Username:</label>
                    <input type="text" class="form-control" id="txt_username" />
                </div>
                <div class="form-group col-md-12">
                    <label for="txt_password">Password: <code>Auto Generate</code></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" id="btnSubmitUser">Submit</button>
            </div>
        </div>
    </div>
</div>