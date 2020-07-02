<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedID;
		var tableIcd = $("#table-icd9").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Icd/icd9",
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
						return row["autonum"];
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
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-icd9\" id=\"icd9_edit_" + row["id"] + "\">" +
										"<i class=\"fa fa-edit\"></i> Edit" +
									"</button>" +
									"<button id=\"icd9_delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-icd9\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-icd9", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus ICD 9 item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Icd/master_icd_9/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableIcd.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		
		$("body").on("click", ".btn-edit-icd9", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			selectedID = id;
			MODE = "edit";
			$("#txt_kode").val($("#kode_" + id).html());
			$("#txt_nama").val($("#nama_" + id).html());
			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-icd9").click(function() {
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
						"request": "tambah_icd9",
						"kode": kode,
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_icd9",
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

	});
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah ICD 9</h5>
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