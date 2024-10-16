<script type="text/javascript">

    $(function() {
        function load_gudang(target) {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					$(target + " option").remove();
					gudangData = response.response_package.response_data;
					for(var a in gudangData) {
						var newOption = document.createElement("OPTION");
						$(newOption).html(gudangData[a].nama).attr({
							"value":gudangData[a].uid
						});
						$(target).append(newOption);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}
        load_gudang("#txt_gudang");
		load_gudang("#target_gudang_import");
        $("#btnImport").click(function() {
            $("#review-import").modal("show");
        });

        $("#upload_csv").submit(function(event) {
            event.preventDefault();
            $("#csv_file_data").html("<h6 class=\"text-center\">Load Data...</h6>");
            var formData = new FormData(this);
            formData.append("request", "so_import_fetch");
            $.ajax({
                url: __HOSTAPI__ + "/SO",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: formData,
                dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success:function(response) {
                    console.clear();
                    console.log(response);

                    var data = response.response_package;

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
                    var filtedData = [];

                    for(var aa in data.row_data) {
                        filtedData.push(data.row_data[aa]);
                    }
                    
                    generated_data = filtedData;
                    $(table_view).addClass("table table-responsive table-bordered table-striped largeDataType").DataTable({
                        data:filtedData,
                        columns : data.column_builder
                    });

                    $("#upload_csv")[0].reset();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });
    });
</script>



<div id="review-import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                    <div class="row">
                                        <div class="col-md-3">
                                            Tujuan Gudang :<br />
                                            <b class="text-info">
                                                <i class="fa fa-info-circle"></i> Pastikan gudang terpilih dengan benar
                                            </b>
                                        </div>
                                        <div class="col-md-9">
                                            <select class="form-control" id="target_gudang_import"></select>
                                        </div>
                                        <div class="col-md-12">
                                            <hr />
                                            <div id="csv_file_data" style="overflow-y: scroll" class="table-responsive"></div>
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