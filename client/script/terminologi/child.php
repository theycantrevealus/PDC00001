<script type="text/javascript">
	$(function(){
		var selectedID;
		var id_term = __PAGES__[2];
		var termData = loadDetailTerm(id_term);
		$(".title-term").html(termData.nama);

		var tableTerminologiItem = $("#table-terminologiItem").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
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
						return "<span id='nama_" + row["id"] + "'>" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-term-item\" id=\"term_item_edit_" + row["id"] + "\" data-toggle='tooltip' title='Edit'>" +
										"<span><i class=\"fa fa-pencil-alt\"></i>Edit</span>" +
									"</button>" +
									"<button id=\"delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-term-item\" data-toggle='tooltip' title='Hapus'>" +
										"<span><i class=\"fa fa-trash\"></i>Hapus</span>" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-term-item", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus terminologi item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi/terminologi-item/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tableTerminologiItem.ajax.reload();
					}
				});
			}
		});


		$("#tambah-item").click(function() {
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";

		});

		$("body").on("click", ".btn-edit-term-item", function() {
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];
			selectedID = id;

			MODE = "edit";
			$("#txt_nama").val($("#nama_" + id).html());
			$("#form-tambah").modal("show");
			return false;
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();

			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah-terminologi-item",
						"nama": nama,
						"id_term": id_term
					};
				} else {
					form_data = {
						"request": "edit-terminologi-item",
						"id": selectedID,
						"nama": nama,
						"id_term": id_term
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Terminologi",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableTerminologiItem.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
	});

	function loadDetailTerm(params){
		var dataTerm = null;

		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Terminologi/terminologi-detail/" + params,
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				dataTerm = response.response_package.response_data[0];
			},
			error: function(response) {
				console.log(response);
			}
		});

		return dataTerm;
	}
</script>


<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah <span class="title-term"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Nama Terminologi Item :</label>
					<input type="text" class="form-control" id="txt_nama" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>