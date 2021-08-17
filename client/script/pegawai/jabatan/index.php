
<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID, TableListPegawai;
		var tableJabatan = $("#table-jabatan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Pegawai/jabatan",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package.response_data;
				}
			},
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row.nama + "</span>";
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var units = "";
                        if(row.unit.length > 0) {
                            for(var ab in row.unit) {
                                units += "<span style=\"margin: 5px 7px\" class=\"badge badge-custom-caption badge-info\"><i class=\"material-icons icon-16pt mr-1 text-white\">business</i> " + row.unit[ab].unit.nama + "</span>";
                            }
                        } else {
                            units = "-";
                        }

                        return units;
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-jabatan\" id=\"jabatan_edit_" + row["uid"] + "\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"jabatan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-jabatan\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

        function render_module(dataMeta, parent = 0) {
            $("#module-table tbody tr").remove();
            for(var key in dataMeta) {

                var newModuleRow = document.createElement("TR");
                $(newModuleRow).attr({
                    "id": "module_row_" + dataMeta[key].id
                }).addClass((dataMeta[key].parent === 0) ? "module_row_" + dataMeta[key].id : "module_row_" + dataMeta[key].parent);

                var newModuleName = document.createElement("TD");
                var newModulePages = document.createElement("TD");
                var newModuleAction = document.createElement("TD");

                $(newModuleAction).html("<div class=\"custom-control custom-checkbox-toggle custom-control-inline mr-1\"></div>").attr("is-child", 1);
                var accessSwitch = document.createElement("input");
                $(accessSwitch).attr({
                    "type": "checkbox",
                    "id": "module-allow-" + dataMeta[key].id
                }).addClass("module-check custom-control-input");

                if(dataMeta[key].checked) {
                    $(accessSwitch).attr({
                        "checked": "checked"
                    });
                }

                $(newModuleAction).find(".custom-control").prepend(accessSwitch).append(
                    "<label class=\"custom-control-label\" for=\"module-allow-" + dataMeta[key].id + "\">Yes</label>"
                );

                $(newModuleName).html("<span style=\"" + ((dataMeta[key].level > 1) ? "" : "font-weight: bolder") + "\" class=\"wrap_content " + ((dataMeta[key].level > 1) ? "" : "text-info") + "\">" + dataMeta[key].nama + "</span>")
                $(newModulePages).html("<a href=\"" + __HOSTNAME__ + "/" + dataMeta[key].identifier + "\"><span class=\"badge badge-success\"><i style=\"margin-right: 8px;\" class=\"fa fa-link\"></i>" + __HOSTNAME__ + "</span><span class=\"badge badge-warning\">/" + dataMeta[key].identifier + "</span>");

                $(newModuleRow).append(newModuleName);
                $(newModuleRow).append(newModulePages);
                $(newModuleRow).append(newModuleAction);
                if(dataMeta[key].parent == 0) {
                    $("#module-table tbody").append(newModuleRow);
                } else {
                    if($("tr.module_row_" + dataMeta[key].parent).length === 0) {
                        $(newModuleRow).insertAfter($("#module-table tbody tr#module_row_" + dataMeta[key].parent));
                    } else {
                        $(newModuleRow).insertAfter($("tr.module_row_" + dataMeta[key].parent + ":eq(" + ($("tr.module_row_" + dataMeta[key].parent + ":last-child").length - 1) + ")"));
                    }

                    var paddingSet = ($("#module_row_" + dataMeta[key].parent).css("padding-left") == undefined) ? 0 : $("#module_row_" + dataMeta[key].parent).css("padding-left");
                    $(newModuleName).css({
                        "padding-left": (paddingSet + (25 * parseInt(dataMeta[key].level))) + "px"
                    });
                }
            }
        }


		$("body").on("click", ".btn-delete-jabatan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus jabatan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori/pegawai_jabatan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableJabatan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-jabatan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";

			//Load Pegawai dengan jabatan ini
            $("#form-tambah").modal("show");

            if(TableListPegawai === undefined) {
                TableListPegawai = $("#pegawai-jabatan").DataTable({
                    processing: true,
                    serverSide: true,
                    sPaginationType: "full_numbers",
                    bPaginate: true,
                    lengthMenu: [[10, 20, -1], [10, 20, "All"]],
                    serverMethod: "POST",
                    "ajax":{
                        url: __HOSTAPI__ + "/Pegawai",
                        type: "POST",
                        data: function(d) {
                            d.request = "get-pegawai-jabatan";
                            d.jabatan = $("#txt_nama").attr("uid");
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
                            response.recordsTotal = response.response_package.recordsFiltered;
                            response.recordsFiltered = response.response_package.recordsTotal;

                            return returnedData;
                        }
                    },
                    autoWidth: false,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari Nama Pegawai"
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
                                return row.created_at_parsed;
                            }
                        }
                    ]
                });
            } else {
                TableListPegawai.ajax.reload();
            }

            $.ajax({
                url:__HOSTAPI__ + "/Pegawai/jabatan_detail/" + uid,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    var unitUIDWatcher = [];
                    var jabatanDetail = response.response_package.response_data[0];
                    for(var abU in jabatanDetail.unit) {
                        unitUIDWatcher.push(jabatanDetail.unit[abU].unit.uid);
                    }

                    var allUnit = response.response_package.all_unit['response_data'];
                    $("#unit_loader").html("");
                    for(var uK in allUnit) {
                        $("#unit_loader").append("<div class=\"col-lg-4\">" +
                                "<div class=\"custom-control custom-checkbox-toggle custom-control-inline\">" +
                                "<input type=\"checkbox\" id=\"unit_jabatan_" + allUnit[uK].uid + "\" class=\"unit_check custom-control-input\" " + ((unitUIDWatcher.indexOf(allUnit[uK].uid) < 0) ? "" : "checked=\"checked\"") + ">" +
                                "<label class=\"custom-control-label\" for=\"unit_jabatan_" + allUnit[uK].uid + "\">Yes</label>" +
                                "<span style=\"position: absolute; left: 110%; top: 1px; width: 200px; padding: 0 5px;\">" + allUnit[uK].nama + "</span>" +
                                "</div>" +
                                "<br /><br />" +
                            "</div>");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });

            $.ajax({
                url:__HOSTAPI__ + "/Pegawai/jabatan_modul/" + uid,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {
                    $("#txt_nama").val($("#nama_" + uid).html()).attr({
                        uid: uid
                    });

                    render_module(response.response_package.build);


                    $("#modal-large-title").html("Edit Jabatan");
                    $("#target-nama-jabatan").html($("#nama_" + uid).html());
                    TableListPegawai.ajax.reload();
                },
                error: function(response) {
                    console.log(response);
                }
            });
			return false;
		});

        $("body").on("click", ".unit_check", function() {
            var id = $(this).attr("id").split("_");
            id = id[id.length - 1];
            $.ajax({
                url:__HOSTAPI__ + "/Pegawai",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    "request": "update_unit_jabatan",
                    "uid": selectedUID,
                    "unit": id,
                    "unit_switch": ($(this).is(":checked")) ? "Y" : "N"
                },
                success:function(resp) {
                    if(resp.response_package.response_result > 0) {
                        tableJabatan.ajax.reload();
                    }
                }
            });
        });

        $("body").on("click", ".module-check", function() {
            var id = $(this).attr("id").split("-");
            id = id[id.length - 1];
            $.ajax({
                url:__HOSTAPI__ + "/Pegawai",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: {
                    "request": "update_jabatan_access",
                    "uid": selectedUID,
                    "modul": id,
                    "accessType": ($(this).is(":checked")) ? "Y" : "N"
                },
                success:function(resp) {
                    if(resp.response_package.response_result > 0) {
                        //
                    }
                    console.log(resp);
                }
            });
        });

		$("#tambah-jabatan").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Jabatan");

		});

		$("#btnSubmit").click(function() {
		    var me = $(this);
		    var lastDOM = me.html();
		    me.removeClass("btn-info").addClass("btn-warning").attr({
                "disabled": "disabled"
            }).html("<span><i class=\"fa fa-sync\"></i> Processing...</span>");

			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_jabatan",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_jabatan",
						"uid": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Pegawai",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableJabatan.ajax.reload();
                        me.removeClass("btn-warning").addClass("btn-info").removeAttr("disabled").html(lastDOM);
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Jabatan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label for="txt_nama">Nama Jabatan:</label>
                                <input type="text" class="form-control" id="txt_nama" />
                            </div>
                            <div class="col-lg-12">
                                <div class="alert alert-soft-warning d-flex align-items-center card-margin" role="alert">
                                    <i class="material-icons mr-3">error_outline</i>
                                    <div class="text-body"><strong>Update Akses</strong> Akses semua pegawai dengan jabatan ini akan disamakan. <code>MOHON BERHATI-HATI</code></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"></li>
                        <li class="list-group-item">
                            <div class="row" id="unit_loader"></div>
                        </li>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5>Pegawai dengan jabatan [<b class="text-info" id="target-nama-jabatan"></b>]</h5>
                                    <br />
                                    <table class="table table-bordered largeDataType" id="pegawai-jabatan">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">No</th>
                                            <th style="width: 50%;">Pegawai</th>
                                            <th>Tanggal Daftar</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-lg-6">
                                    <h5>Akses Jabatan</h5>
                                    <br />
                                    <table class="table table-bordered largeDataType" id="module-table">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th class="wrap_content">Module</th>
                                            <th style="width: 30%;">Pages</th>
                                            <th class="wrap_content">Access</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">
                    <span>
                        <i class="fa fa-ban"></i> Kembali
                    </span>
                </button>
				<button type="button" class="btn btn-primary" id="btnSubmit">
                    <span>
                        <i class="fa fa-check-circle"></i> Submit
                    </span>
                </button>
			</div>
		</div>
	</div>
</div>