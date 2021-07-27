<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedID;
		var tableIcd = $("#table-icd10").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[15, 50, -1], [15, 50, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Icd",
				type: "POST",
				data: function(d){
					d.request = "get_icd_10_back_end_dt";
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Nomor Kwitansi"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kode_" + row["id"] + "\">" + row["kode"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["id"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-icd10\" id=\"icd10_edit_" + row["id"] + "\">" +
										"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"icd10_delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-icd10\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-icd10", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus ICD 10 item?");
			
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Icd/master_icd_10/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						console.log(response);
						tableIcd.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		
		$("body").on("click", ".btn-edit-icd10", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			selectedID = id;
			MODE = "edit";
			$("#txt_kode").val($("#kode_" + id).html());
			$("#txt_nama").val($("#nama_" + id).html());
			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-icd10").click(function() {
			$("#txt_kode").val("");
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";
		});


		$("#btnSubmit").click(function() {
			var kode = $("#txt_kode").val();
			var nama = $("#txt_nama").val();
			if(kode != "" && nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_icd10",
						"kode": kode,
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_icd10",
						"id": selectedID,
						"kode": kode,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Icd",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#txt_kode").val("");
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableIcd.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});









        $("#importData").click(function () {
            $("#review-import").modal("show");
        });





        $("#upload_csv").submit(function(event) {
            event.preventDefault();
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "icd_import_fetch");
            formData.append("target", "master_icd_10");
            $.ajax({
                url: __HOSTAPI__ + "/Icd",
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
                    $("#review-import").modal();

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
                    $(table_view).addClass("table table-bordered table-striped largeDataType").DataTable({
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
                        url: __HOSTAPI__ + "/Icd",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_icd",
                            target: "master_icd_10",
                            data_import:generated_data
                        },
                        success:function(response)
                        {
                            var html = "Imported : " + response.response_package.success_proceed + "<br />";
                            $("#csv_file_data").html(html);
                            tableIcd.ajax.reload();
                            $("#review-import").modal("hide");
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

	});
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah ICD 10</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-6">
					<label for="txt_no_skp">Kode Diagnosa:</label>
					<input type="text" maxlength="6" class="form-control" id="txt_kode" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Diagnosa:</label>
					<textarea class="form-control" id="txt_nama" rows="3"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>


<div id="review-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Harga Tindakan (Poli)</h5>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <hr />
                                            <div id="csv_file_data" class="table-responsive"></div>
                                        </div>
                                    </div>
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