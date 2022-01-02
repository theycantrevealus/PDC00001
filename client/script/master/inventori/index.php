<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;

		var tableGudang = $("#table-item").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d) {
                    d.request = "get_item_back_end";
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
						var kategoriObat = "";
						for(var kategoriObatKey in row["kategori_obat"]) {
						    if(row["kategori_obat"][kategoriObatKey].kategori != null) {
                                kategoriObat += "<span style=\"margin: 5px;\" class=\"badge badge-custom-caption badge-outline-info\"><i class=\"fa fa-tag\"></i> " + row["kategori_obat"][kategoriObatKey].kategori + "</span>";
                            }
						}

						return 		"<div class=\"row\">" +
										"<div class=\"col-md-2\">" +
											"<center><img style=\"border-radius: 5px;\" src=\"" + __HOST__ + row.image + "\" width=\"60\" height=\"60\" /></center>" +
										"</div>" +
										"<div class=\"col-md-10\">" +
											"<b><i>" + ((row["kode_barang"] == undefined) ? "[KODE_BARANG]" : row["kode_barang"].toUpperCase()) + "</i></b><br />" +
											"<strong style=\"display: block\">" + row["nama"].toUpperCase() + "</strong>" +
											kategoriObat +
										"</div>" +
									"</div>";
					}
				},
				/*{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["kode_barang"].toUpperCase() + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"].toUpperCase() + "</span>";
					}
				},*/
				{
					"data" : null, render: function(data, type, row, meta) {
						if(row["kategori"] == undefined) {
							return "-";
						} else {
							return "<span id=\"nama_" + row["uid"] + "\">" + row["kategori"].nama.toUpperCase() + "</span>";
						}
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						if(row["manufacture"] == undefined) {
							return "-";
						} else {
							return "<span id=\"nama_" + row["uid"] + "\">" + row["manufacture"].nama + "</span>";
						}
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/master/inventori/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm\">" +
										"<i class=\"fa fa-pencil-alt\"></i> Edit" +
									"</a>" +
									"<button id=\"gudang_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-gudang\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("#btn-import").click(function () {
		    $("#form-import").modal("show");
        });

		var generated_data = [];

        $('#upload_csv').on('submit', function(event) {
            event.preventDefault();
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "master_inv_import_fetch");
            $.ajax({
                url: __HOSTAPI__ + "/Inventori",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success:function(response)
                {
                    var data = response.response_package;
                    generated_data = data.row_data;
                    $("#csv_file_data").html("");
                    var thead = "";
                    if(data.column)
                    {
                        thead += "<tr>";
                        for(var count = 0; count < data.column.length; count++)
                        {
                            thead += "<th>"+data.column[count]+"</th>";
                        }
                        thead += "</tr>";
                    }
                    var table_view = document.createElement("TABLE");
                    $(table_view).append("<thead class=\"thead-dark\">" + thead + "</thead>");
                    $("#csv_file_data").append(table_view);
                    $(table_view).addClass("table table-bordered table-striped").DataTable({
                        data:data.row_data,
                        columns : data.column_builder
                    });

                    $("#upload_csv")[0].reset();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

        $("#import_data").click(function () {
            Swal.fire({
                title: 'Proses import data?',
                showDenyButton: true,
                confirmButtonText: `Ya`,
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#csv_file_data").html("<h6 class=\"text-center\">Importing...</h6>");
                    $("#import_data").attr("disabled", "disabled");
                    $("#csv_file").attr("disabled", "disabled");
                    $.ajax({
                        url: __HOSTAPI__ + "/Inventori",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_master_inv",
                            data_import:generated_data,
                            super: "farmasi"
                        },
                        success:function(response)
                        {
                            console.log(response.response_package);
                            var html = "Imported : " + response.response_package.success_proceed + "<br />";
                            /*html += "Imported : " + response.response_package.success_proceed + "<br />";
                            html += "Imported : " + response.response_package.success_proceed + "<br />";*/

                            var failedData = response.response_package.failed_data;
                            console.log(failedData);
                            var failedResult = document.createElement("table");
                            $(failedResult).addClass("table").append("<thead class=\"thead-dark\">" +
                            "<tr>" +
                            "<th>Nama</th>" +
                            "<th>Kategori</th>" +
                            "<th>Satuan</th>" +
                            "<th>Nama Generik</th>" +
                            "<th>Nama RKO</th>" +
                            "<th>Generik</th>" +
                            "<th>Antibiotik</th>" +
                            "<th>Narkotika</th>" +
                            "<th>Psikotropika</th>" +
                            "<th>Fornas</th></tr></thead><tbody></tbody>");

                            $("#csv_file_data").html(html).append(failedResult);
                            $(failedResult).DataTable({
                                data: failedData,
                                columns: [
                                    { data: "nama" },
                                    { data: "kategori" },
                                    { data: "satuan" },
                                    { data: "nama_generik" },
                                    { data: "nama_rko" },
                                    { data: "generik" },
                                    { data: "antibiotik" },
                                    { data: "narkotika" },
                                    { data: "psikotropika" },
                                    { data: "fornas" }
                                ]
                            });

                            tableGudang.ajax.reload();
                            $("#import_data").removeAttr("disabled");
                            $("#csv_file").removeAttr("disabled");
                        },
                        error: function (response) {
                            $("#csv_file_data").html(response);
                            console.log(response);
                        }
                    });
                } else if (result.isDenied) {
                    //
                }
            });
        });







		$("body").on("click", ".btn-delete-gudang", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus gudang item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori/master_inv_gudang/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableGudang.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-gudang", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Gudang");
			return false;
		});

		$("#tambah-gudang").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Gudang");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_gudang",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_gudang",
						"uid": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Inventori",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableGudang.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>



<div id="form-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white d-flex align-items-center">
                                <h5 class="card-header__title flex m-0">CSV</h5>
                                <form id="upload_csv" method="post" enctype="multipart/form-data">
                                    <input type="file" name="csv_file" id="csv_file" accept=".csv" />
                                    <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />
                                </form>
                            </div>
                            <div class="card-body tab-content">
                                <div class="tab-pane active show fade">
                                    <div id="csv_file_data"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="import_data">Import</button>
            </div>
        </div>
    </div>
</div>