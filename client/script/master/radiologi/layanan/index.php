<script type="text/javascript">
	$(function(){
		var selectedID;
		loadJenisTindakan();
		var tableLayanan = $("#table-layanan-radiologi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Radiologi/tindakan",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package.response_data;
				}
			},
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
						return row["nama"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['jenis'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
								"<button data-uid='" + row['uid'] + "' data-jenis='"+ row['uid_jenis'] +"' data-nama='"+ row['nama'] +"' class=\"btn btn-info btn-sm btnEdit\" data-toggle='tooltip' title='Edit'>" +
                                "<span><i class=\"fa fa-pencil-alt\"></i>Edit</span>" +
                                "</button>" +

								"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" data-toggle='tooltip' title='Hapus'>" +
									"<span><i class=\"fa fa-trash\"></i>Hapus</span>" +
								"</button>" +
							"</div>";
					}
				}
			]
		});

		$("#table-layanan-radiologi tbody").on("click", ".btn-delete-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Radiologi/master_tindakan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						//console.log(response);
						tableLayanan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
		
		$("#btnTambahData").click(function() {
			$("#nama").val("");
			$("#jenis").val("").trigger('change');
			$("#form-tambah").modal("show");
			MODE = "tambah";
		});

		$("#table-layanan-radiologi tbody").on("click", ".btnEdit", function() {
			selectedID = $(this).data("uid");
			let jenis = $(this).data("jenis");
			let nama = $(this).data("nama");

			MODE = "edit";
			$("#nama").val(nama);
			$("#jenis").val(jenis).trigger('change');
			$("#form-tambah").modal("show");

			return false;
		});

		$("#btnSubmit").click(function() {
			var nama = $("#nama").val();
			var jenis = $("#jenis").val();

			if(nama != "" && jenis != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah-tindakan",
						"nama": nama,
						"jenis": jenis
					};
				} else {
					form_data = {
						"request": "edit-tindakan",
						"uid": selectedID,
						"jenis": jenis,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Radiologi",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#nama").val("");
						$("#jenis").val("");
						$("#form-tambah").modal("hide");
						tableLayanan.ajax.reload();
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
            formData.append("request", "radiologi_import_fetch");
            $.ajax({
                url: __HOSTAPI__ + "/Radiologi",
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
                        url: __HOSTAPI__ + "/Radiologi",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_radiologi",
                            data_import:generated_data
                        },
                        success:function(response) {
                            var html = "Imported : " + response.response_package.proceed.length + "<br />";
                            
							var failedData = response.response_package.failed_data;
                            console.log(failedData);
                            var failedResult = document.createElement("table");
                            $(failedResult).addClass("table").append("<thead class=\"thead-dark\">" +
                            "<tr>" +
                            "<th>Kategori</th>" +
                            "<th>Nama</th>" +
                            "<th>Mitra</th>" +
                            "<th>Harga</th></tr></thead><tbody></tbody>");

                            $("#csv_file_data").html(html).append(failedResult);
                            $(failedResult).DataTable({
                                data: failedData,
                                columns: [
                                    { data: "kategori" },
                                    { data: "nama" },
                                    { data: "mitra" },
                                    { data: "harga" }
                                ]
                            });

                            tableLayanan.ajax.reload();
                            //$("#review-import").modal("hide");
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
	
	function loadJenisTindakan(){
		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Radiologi/jenis",
			type: "GET",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
            	var MetaData = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#jenis").append(selection);
	                }
					
					$("#jenis").select2({
						dropdownParent: $('#form-tambah')
					});
				}
            },
            error: function(response) {
                console.log(response);
            }
		});
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Tindakan Radiologi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-12 col-md-12 mb-3 form-group">
					<label for="">Nama Layanan / Tindakan:</label>
					<input type="text" name="nama" id="nama" class="form-control">
				</div>
					<div class="col-12 col-md-12 mb-3 form-group">
					<label for="">Jenis Layanan:</label>
					<select class="form-control" id="jenis" nama="jenis">
						<option value="">Pilih</option>
					</select>
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
                <h5 class="modal-title" id="modal-large-title">Import Radiologi</h5>
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