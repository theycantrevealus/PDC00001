<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		generate_gudang("#txt_gudang");
		$("#txt_gudang").select2();
		function generate_gudang(target, selected = "") {
			var gudangData;
			$.ajax({
				url:__HOSTAPI__ + "/Inventori/gudang",
				async:false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					gudangData = response.response_package.response_data;
					$(target).find("option").remove();
					$(target).append("<option " + ((selected == "") ? "selected=\"selected\"" : "") + " value=\"\">Tidak Ada Gudang</option>");
					for(var a = 0; a < gudangData.length; a++) {
						$(target).append("<option " + ((selected == gudangData[a].uid) ? "selected=\"selected\"" : "") + " value=\"" + gudangData[a].uid + "\">" + gudangData[a].nama + "</option>");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return gudangData;
		}
		var tableUnit = $("#table-unit").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Unit",
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
						return "<b><span id=\"kode_" + row["uid"] + "\">" + row["kode"] + "</span></b> - <span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						if(row["gudang"] == undefined) {
							return "<span id=\"gudang_" + row["uid"] + "\" gudang=\"\">-</span>";
						} else {
							return "<span id=\"gudang_" + row["uid"] + "\" gudang=\"" + row["gudang"]["uid"] + "\">" + row["gudang"]["nama"] + "</span>";
						}
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-unit\" id=\"unit_edit_" + row["uid"] + "\">" +
										"<i class=\"fa fa-pencil-alt\"></i> Edit" +
									"</button>" +
									"<button id=\"unit_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-unit\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-unit", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus unit item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Unit/master_unit/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableUnit.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-unit", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#txt_kode").val($("#kode_" + uid).html());
			generate_gudang("#txt_gudang", $("#gudang_" + uid).attr("gudang"));
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Unit");
			return false;
		});

		$("#tambah-unit").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Unit");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var kode = $("#txt_kode").val();
			var gudang = $("#txt_gudang").val();

			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_unit",
						"nama": nama,
						"kode": kode,
						"gudang": gudang
					};
				} else {
					form_data = {
						"request": "edit_unit",
						"uid": selectedUID,
						"nama": nama,
						"kode": kode,
						"gudang": gudang
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Unit",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#txt_kode").val("");
						generate_gudang("#txt_gudang");
						$("#form-tambah").modal("hide");
						tableUnit.ajax.reload();
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
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Unit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Nama Unit:</label>
					<input type="text" class="form-control" id="txt_nama" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Kode Unit:</label>
					<input type="text" class="form-control" id="txt_kode" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Gudang Unit:</label>
					<select class="form-control" id="txt_gudang"></select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>