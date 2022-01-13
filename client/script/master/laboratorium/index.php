<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var tableLab = $("#table-lab").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Laboratorium",
				type: "POST",
                data: function(d) {
                    d.request = "get_lab_backend";
                },
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
            language: {
                search: "",
                searchPlaceholder: "Cari Laboratorium"
            },
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kode_" + row["uid"] + "\">" + row["kode"].toUpperCase() + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
					    if(row.spesimen !== undefined && row.spesimen !== null) {
                            return row.spesimen.nama;
                        } else {
                            return "-";
                        }
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        var mitraRaw = row.mitra_all;
                        var mitraSelected = row.mitra_nama;
                        var mitraParse = "<div class=\"row\">";

                        for(var mK in mitraRaw) {
                            mitraParse += "<div class=\"col-4\" style=\"" + ((mitraSelected.indexOf(mitraRaw[mK]) > -1) ? "opacity:1" : "opacity:.5;text-decoration:line-through") + "\">" + ((mitraSelected.indexOf(mitraRaw[mK]) > -1) ? "<i class=\"fa fa-check-circle text-success\"></i>" : "<i class=\"fa fa-times-circle text-danger\"></i>") + " <b class=\"" + ((mitraSelected.indexOf(mitraRaw[mK]) > -1) ? "text-success" : "text-danger") + "\">" + mitraRaw[mK] + "</b></div>";
                        }
                        mitraParse += "</div>";
                        return mitraParse;
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/master/laboratorium/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-lab\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</a>" +
									"<button id=\"lab_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-lab\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-lab", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus lab laboratorium?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Laboratorium/master_lab/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableLab.ajax.reload();
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
            formData.append("request", "laboratorium_import_fetch_nilai");
            $.ajax({
                url: __HOSTAPI__ + "/Laboratorium",
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
                        url: __HOSTAPI__ + "/Laboratorium",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_laboratorium",
                            data_import:generated_data
                        },
                        success:function(response)
                        {
                            console.clear();
                            console.log(response);
                            var html = "Imported : " + response.response_package.success_proceed + "<br />";

                            
                            var failedData = response.response_package.failed_data;
                            console.log(failedData);
                            var failedResult = document.createElement("table");
                            $(failedResult).addClass("table").append("<thead class=\"thead-dark\">" +
                            "<tr>" +
                            "<th>Kode</th>" +
                            "<th>Kategori</th>" +
                            "<th>Subkategori</th>" +
                            "<th>Nama</th>" +
                            "<th>Mitra</th>" +
                            "<th>Harga</th></tr></thead><tbody></tbody>");

                            $("#csv_file_data").html(html).append(failedResult);
                            $(failedResult).DataTable({
                                data: failedData,
                                columns: [
                                    { data: "kode" },
                                    { data: "kategori" },
                                    { data: "subkategori" },
                                    { data: "nama" },
                                    { data: "mitra" },
                                    { data: "harga" }
                                ]
                            });
                            

                            tableLab.ajax.reload();
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
</script>

<div id="review-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Tarif Laboratorium</h5>
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