<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		load_jalur_loket();
		var localMetaData = {};
		function load_jalur_loket(currentLoket = []) {
			$.ajax({
				url:__HOSTAPI__ + "/Anjungan/all_loket",
				async: false,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type:"GET",
				success:function(response) {
					var data = response.response_package.response_data;
					$("#jalur_loket tbody tr").remove();
					for(var key in data) {
						var autonum = (parseInt(key) + 1);
						var newRow = document.createElement("TR");
						var newLoketNum = document.createElement("TD");
						var newLoketName = document.createElement("TD");
						var newLoketJalur = document.createElement("TD");

						//var allowJalur = data[key].allow_jalur.split(",");
						$(newLoketNum).html("<h5 class=\"autonum\">" + autonum + "</h5>");
						$(newLoketName).html(data[key].nama_loket);
						$(newLoketJalur).html("<input value=\"" + data[key].uid + "\" " + ((currentLoket.indexOf(data[key].uid) < 0) ? "" : "checked=\"checked\"") + " type=\"checkbox\" class=\"form-control allow-jalur\" />");

						$(newRow).append(newLoketNum);
						$(newRow).append(newLoketName);
						$(newRow).append(newLoketJalur);

						$("#jalur_loket tbody").append(newRow);
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}


		$("body").on("change", ".allow-jalur", function() {
			//
		});


		var tableAnjungan = $("#table-anjungan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Anjungan/anjungan_jenis",
				async: false,
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var data = response.response_package.response_data;
					for(var key in data) {
						if(localMetaData[data[key].uid] != undefined) {
							localMetaData[data[key].uid] = {
								"autonum" : data[key].autonum,
								"nama" : data[key].nama,
								"kode" : data[key].kode,
								"allow_jalur" : data[key].allow_jalur
							};
						}

						localMetaData[data[key].uid] = {
							"autonum" : data[key].autonum,
							"nama" : data[key].nama,
							"kode" : data[key].kode,
							"allow_jalur" : data[key].allow_jalur
						};
					}
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
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kode_" + row["uid"] + "\">" + ((row["kode"] == undefined) ? "-" : row["kode"]) + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-anjungan\" id=\"anjungan_edit_" + row["uid"] + "\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"anjungan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-anjungan\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-anjungan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus jenis anjungan?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Anjungan/antrian_jenis/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableAnjungan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-anjungan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";

			$("#txt_nama").val($("#nama_" + uid).html());
			$("#txt_kode").val($("#kode_" + uid).html());
			if(localMetaData[uid].allow_jalur == undefined) {
				load_jalur_loket();
			} else {
				load_jalur_loket(localMetaData[uid].allow_jalur.split(","));
			}

			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Anjungan");
			return false;
		});

		$("#tambah-anjungan").click(function() {
			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Anjungan");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var kode = $("#txt_kode").val();
			var allow_jalur = [];
			if(nama != "" && kode != "") {
				$("#jalur_loket tbody tr").each(function() {
					if($(this).find("td:eq(2) input").is(":checked")) {
						if(allow_jalur.indexOf($(this).find("td:eq(2) input").val()) < 0) {
							allow_jalur.push($(this).find("td:eq(2) input").val());
						}
					}
				});

				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "master_tambah_jenis_antrian",
						"kode": kode,
						"nama": nama,
						"allow_jalur": allow_jalur
					};
				} else {
					form_data = {
						"request": "master_edit_jenis_antrian",
						"uid": selectedUID,
						"kode": kode,
						"nama": nama,
						"allow_jalur": allow_jalur
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
				<h5 class="modal-title" id="modal-large-title">Jenis Antrian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label for="txt_no_skp">Nama Jenis Antrian:</label>
							<input type="text" class="form-control" id="txt_nama" />
						</div>
						<div class="form-group col-md-6">
							<label for="txt_no_skp">Kode Antrian (Hanya Kode):</label>
							<input type="text" class="form-control" id="txt_kode" />
							<br />
							<i>[KODE_ANTRIAN]-[ANTRIAN].<br />Ex : "A-0001"</i>
						</div>
					</div>
					<div class="col-md-6">
						<table class="table table-bordered" id="jalur_loket">
							<thead class="thead-dark">
								<tr>
									<th class="wrap_content">No</th>
									<th>Loket</th>
									<th class="wrap_content">Jalur</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>