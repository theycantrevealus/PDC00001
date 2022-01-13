<script type="text/javascript">
	$(function(){

		var MODE = "tambah", selectedUID;
		var tablePasien = $("#table-pasien").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Pasien",
                type: "POST",
                data: function(d) {
                    d.request = "get_pasien_back_end";
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
                searchPlaceholder: "Cari Pasien"
            },
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"norm_" + row["uid"] + "\">" + row["no_rm"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tgllahir_" + row["uid"] + "\">" + row["tanggal_lahir"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"jenkel_" + row["uid"] + "\">" + row["jenkel"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tgldaftar_" + row["uid"] + "\">" + row["tgl_daftar"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        if(__MY_PRIVILEGES__.response_data[0].uid == __UIDADMIN__) {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        /*"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
                                            "<i class=\"fa fa-list\"></i> Detail" +
                                        "</button>" +*/
                                        "<a href=\"" + __HOSTNAME__ + "/pasien/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-pasien\" data-toggle='tooltip' title='Edit'>" +
                                            "<span><i class=\"fa fa-edit\"></i>Edit</span>" +
                                        "</a>" +
                                        "<button id=\"pasien_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-pasien\" data-toggle='tooltip' title='Hapus'>" +
                                            "<span><i class=\"fa fa-trash\"></i>Hapus</span>" +
                                        "</button>" +
                                    "</div>";
                        } else {
                            return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                                        /*"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
                                            "<i class=\"fa fa-list\"></i> Detail" +
                                        "</button>" +*/
                                        "<a href=\"" + __HOSTNAME__ + "/pasien/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-pasien\" data-toggle='tooltip' title='Edit'>" +
                                            "<span><i class=\"fa fa-edit\"></i>Edit</span>" +
                                        "</a>" +
                                    "</div>";
                        }
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-pasien", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus pasien?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Pasien/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePasien.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

        var generated_data = [];

		$("#btn-import").click(function () {
		    $("#form-import").modal("show");
		    return false;
        });

        $('#upload_csv').on('submit', function(event) {
            event.preventDefault();
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "master_pasien_import_fetch");
            $.ajax({
                url: __HOSTAPI__ + "/Pasien",
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
                    $(table_view).addClass("table table-bordered table-striped table-responsive").DataTable({
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
                        url: __HOSTAPI__ + "/Pasien",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type: "POST",
                        data: {
                            request: "proceed_import_pasien",
                            data_import:generated_data
                        },
                        success:function(response)
                        {
                            var html = "Imported : " + response.response_package.success_proceed + "<br /><br /><h5>Failed import data:</h5>";

                            var failedData = response.response_package.failed_data;
                            var failedResult = document.createElement("table");
                            $(failedResult).addClass("table").append("<thead class=\"thead-dark\">" +
                            "<tr>" +
                            "<th>No RM</th>" +
                            "<th>NIK</th>" +
                            "<th>Nama</th>" +
                            "<th>Tempat Lahir</th>" +
                            "<th>Tanggal Lahir</th></tr></thead><tbody></tbody>");

                            $("#csv_file_data").html(html).append(failedResult);
                            $(failedResult).DataTable({
                                data: failedData,
                                columns: [
                                    { data: "no_rm" },
                                    { data: "nik" },
                                    { data: "nama" },
                                    { data: "tempat_lahir" },
                                    { data: "tanggal_lahir" }
                                ]
                            });
                            tablePasien.ajax.reload();
                            $("#import_data").removeAttr("disabled");
                            $("#csv_file").removeAttr("disabled");
                            console.clear();
                            console.log(response);
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


<div id="form-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">Import Pasien</h5>
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
                                    <div id="csv_file_data" class="table-responsive"></div>
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