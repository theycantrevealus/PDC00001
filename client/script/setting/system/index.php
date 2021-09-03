<script type="text/javascript">
    $(function() {

        var MODE = "add";
        var currentID = "";
        $("#btnTambahSetting").click(function () {
            MODE = "add";
            $("#modal-large-title").html("Tambah Setting");
            $("#form-tambah-setting").modal("show");
        });

        $("body").on("click", ".edit_setting", function () {
            MODE = "edit";
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            currentID = id;

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Setting/admin_load_setting_detail/" + id,
                type: "GET",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function (response) {
                    var data = response.response_package.response_data[0];
                    $("#form-tambah-setting").modal("show");
                    $("#txt_param_iden").val(data.param_iden);
                    $("#txt_param_value").val(data.param_value);
                    $("#txt_param_column").val(data.param_table_column);
                    $("#txt_param_caption").val(data.param_table_caption);

                    if(data.param_table_link !== null && data.param_table_link !== undefined) {
                        $("#txt_param_table").append("<option title=\"" + data.param_table_link + "\" value=\"" + data.param_table_link + "\">" + data.param_table_link + "</option>");
                        $("#txt_param_table").select2("data", {
                            id: data.param_table_link,
                            text: data.param_table_link
                        });
                        $("#txt_param_table").trigger("change");
                    }



                },
                error: function (response) {
                    //
                }
            });
            return false;
        });

        $("#btnSubmitSetting").click(function () {
            var identifier = $("#txt_param_iden").val();
            var idenvalue = $("#txt_param_value").val();
            var param_table_link = $("#txt_param_table option:selected").val();
            var param_table_column = $("#txt_param_column").val();
            var param_table_caption = $("#txt_param_caption").val();

            if(identifier !== "") {
                var paramBuilder = {};
                if(MODE === "add") {
                    paramBuilder = {
                        request: "tambah_setting",
                        param_iden : identifier,
                        param_value: idenvalue,
                        param_table_link: param_table_link,
                        param_table_column: param_table_column,
                        param_table_caption: param_table_caption
                    };
                } else {
                    paramBuilder = {
                        request: "edit_setting",
                        id: currentID,
                        param_iden : identifier,
                        param_value: idenvalue,
                        param_table_link: param_table_link,
                        param_table_column: param_table_column,
                        param_table_caption: param_table_caption
                    };
                }

                $.ajax({
                    async: false,
                    url: __HOSTAPI__ + "/Setting",
                    type: "POST",
                    data: paramBuilder,
                    beforeSend: function (request) {
                        request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                    },
                    success: function (response) {
                        $("#txt_param_iden").val("");
                        $("#txt_param_value").val("");
                        $("#txt_param_column").val("");
                        $("#txt_param_caption").val("");
                        $("#form-tambah-setting").modal("hide");
                        console.log(response);
                        reloadSetting();
                    },
                    error: function (response) {
                        //
                    }
                });
            }
        });

        function checkData() {
            $.ajax({
                url: __HOSTAPI__ + "/Setting",
                async: false,
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    request: "check_data_value",
                    table_name: "",
                    column: "",
                    caption: ""
                },
                success: function (response) {
                    console.log(response);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        $("#txt_param_table").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Data tidak ditemukan";
                }
            },
            placeholder: "Cari Data",
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/Setting/get_tables_list",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term
                    };
                },
                cache: true,
                processResults: function (response) {
                    return {
                        results: $.map(response.response_package, function (item) {
                            return {
                                text: item.table_name,
                                id: item.table_name
                            }
                        })
                    };
                }
            }
        }).on("select2:select", function(e) {
            var data = e.params.data;
        });

        function reloadSetting() {
            $.ajax({
                url:__HOSTAPI__ + "/Setting/admin_load_setting",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var data = response.response_package.response_data;
                    $("#setting-loader").html("");
                    for(var a in data) {
                        var validation_data = "";
                        var isValid = 0;
                        if(data[a].valid !== undefined && data[a].valid !== null) {
                            if(data[a].valid.length > 0) {
                                validation_data = "<h5 class=\"text-success text-right\"><i class=\"fa fa-check-circle\"></i> " + data[a].param_table_link + "<br /><small>[" + data[a].valid[0][data[a].param_table_caption] + "]</small></h5>"
                                isValid = 2;
                            } else {
                                validation_data = "<h5 class=\"text-danger text-right\"><i class=\"fa fa-exclamation-triangle\"></i> Data Tidak Ditemukan</h5>";
                                isValid = 1;
                            }
                        }
                        var valueBuilder = "<input class=\"form-control number_style " + ((isValid === 2) ? "text-success" : ((isValid === 1) ? "text-danger" : "text-info")) + "\" type=\"text\" id=\"identifier_value_" + data[a].id + "\" value=\"" + data[a].param_value + "\" />";
                        /*if(data[a].param_type === "value") {
                            valueBuilder = "<input type=\"text\" id=\"identifier_value_" + data[a].id + "\" value=\"\" />";
                        } else {
                            valueBuilder = "<input type=\"text\" id=\"identifier_value_" + data[a].id + "\" value=\"\" />";
                        }*/
                        $("#setting-loader").append(
                            "<div class=\"col-lg-6\">" +
                            "<div class=\"card row\" style=\"margin: 20px;\">" +
                            "<div class=\"col-12\">" +
                            "<span class=\"badge badge-custom-caption badge-info\"><i class=\"fa fa-tags\"></i> " + data[a].param_iden + "</span><br />" +
                            "<h6 class=\"text-right\">" +
                            "<a href=\"#\" class=\"edit_setting\" id=\"edit_" + data[a].id + "\"><i class=\"fa fa-pencil-alt\"></i> Edit</a>" +
                            "</h6>" +
                            "<br />" +
                            valueBuilder + validation_data +
                            "<br /><br /><br /></div>" +
                            "" +
                            "</div></div>");
                    }

                },
                error: function(response) {
                    console.log(response);
                }
            });
        }

        reloadSetting();

    });
</script>
<div id="form-tambah-setting" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Tambah Setting</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-10">
                        <strong>Param Identifier:</strong>
                        <input type="text" class="form-control" id="txt_param_iden" />
                        <br />
                    </div>
                    <div class="col-lg-12">
                        <strong>Param Value:</strong>
                        <input type="text" class="form-control number_style" id="txt_param_value" />
                        <br />
                    </div>
                    <div class="col-lg-8">
                        <strong>Link Data:</strong>
                        <select class="form-control" id="txt_param_table"></select>
                        <br /><br />
                    </div>
                    <div class="col-lg-6">
                        <strong>Table Identifier:</strong>
                        <input type="text" class="form-control" id="txt_param_column" />
                        <br />
                    </div>
                    <div class="col-lg-6">
                        <strong>Data Caption:</strong>
                        <input type="text" class="form-control" id="txt_param_caption" />
                        <br />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
                <button type="button" class="btn btn-primary" id="btnSubmitSetting">
                    <span>
                        <i class="fa fa-save"></i> Submit
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>