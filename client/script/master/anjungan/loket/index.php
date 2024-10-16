<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var localJenisAnjunganData = {};
		function reload_jenis(currentLoket = []) {
			$.ajax({
				url:__HOSTAPI__ + "/Anjungan/anjungan_jenis",
				async: false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					$("#table-jenis-anjungan tbody tr").remove();
					for(var key in data) {
						var autonum = (parseInt(key) + 1);
						var newRow = document.createElement("TR");
						var newLoketNum = document.createElement("TD");
						var newLoketName = document.createElement("TD");
						var newLoketJalur = document.createElement("TD");

						//var allowJalur = data[key].allow_jalur.split(",");
						$(newLoketNum).html("<h5 class=\"autonum\">" + autonum + "</h5>");
						$(newLoketName).html(data[key].nama);
						$(newLoketJalur).html("<input value=\"" + data[key].uid + "\" " + ((currentLoket.indexOf(data[key].uid) < 0) ? "" : "checked=\"checked\"") + " type=\"checkbox\" class=\"form-control jenis-anjungan\" />");

						$(newRow).append(newLoketNum);
						$(newRow).append(newLoketName);
						$(newRow).append(newLoketJalur);

						$("#table-jenis-anjungan tbody").append(newRow);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		reload_jenis();

		var tableAnjungan = $("#table-anjungan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Anjungan/all_loket",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var data = response.response_package.response_data;
					for(var key in data) {
						for(var KKey in data[key].jenis) {
							if(localJenisAnjunganData[data[key].uid] === undefined) {
								localJenisAnjunganData[data[key].uid] = [];
							}

							if(localJenisAnjunganData[data[key].uid].indexOf(data[key].jenis[KKey].uid) < 0) {
								localJenisAnjunganData[data[key].uid].push(data[key].jenis[KKey].uid);
							}
						}
					}
					return data;
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
						return "<span id=\"nama_" + row["uid"] + "\">" + row.nama_loket + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-mesin\" id=\"mesin_edit_" + row["uid"] + "\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"mesin_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-mesin\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-mesin", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus loket?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Anjungan/master_loket/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						console.log(response);
						tableAnjungan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("#tambah-anjungan").click(function() {
			MODE = "tambah";
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			reload_jenis();
			$("#modal-large-title").html("Tambah Loket");
			return false;
		});

		$("body").on("click", ".btn-edit-mesin", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Loket");
			return false;
		});

		$("#tambah-mesin").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Mesin Anjungan");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};

				if(MODE == "tambah") {
					form_data = {
						"request": "master_tambah_loket",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "master_edit_loket",
						"uid": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Anjungan",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableAnjungan.ajax.reload();
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
				<h5 class="modal-title" id="modal-large-title">Tambah Gudang</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama">Nama Loket:</label>
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