<script type="text/javascript">
	$(function() {
		var MODE = "tambah",
			cat, selectedUID;


		var tableTindakanLab = $("#table-kelas-lab-tindakan").DataTable({
			ajax: {
				async: false,
				url: __HOSTAPI__ + "/Tindakan/kelas/LAB",
				type: "GET",
				headers: {
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc: function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			rowReorder: {
				dataSrc: "autonum"
			},
			columns: [{
					"data": "autonum",
					render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
							"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" cat=\"rj\"" +
							"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
							"</button>" +
							"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" cat=\"rj\">" +
							"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
							"</button>" +
							"</div>";
					}
				}
			]
		});

		tableTindakanLab.on('row-reorder', function(e, diff, edit) {
			var result = [];

			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = tableTindakanLab.row(diff[i].node).data();
				result.push({
					"uid": rowData.uid,
					"position": (diff[i].newPosition + 1)
				});
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: {
					request: "update_position",
					jenis: "RJ",
					data: result
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response) {
					if (response.response_package > 0) {
						notification("success", "Kelas berhasil diurutkan", 3000, "kelas_tindakan_result");
					} else {
						notification("danger", "Kelas tindakan gagal diurutkan", 3000, "kelas_tindakan_result");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});




		var tableTindakanRadio = $("#table-kelas-radio-tindakan").DataTable({
			ajax: {
				async: false,
				url: __HOSTAPI__ + "/Tindakan/kelas/RAD",
				type: "GET",
				headers: {
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc: function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			rowReorder: {
				dataSrc: "autonum"
			},
			columns: [{
					"data": "autonum",
					render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
							"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" cat=\"rj\"" +
							"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
							"</button>" +
							"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" cat=\"rj\">" +
							"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
							"</button>" +
							"</div>";
					}
				}
			]
		});

		tableTindakanRadio.on('row-reorder', function(e, diff, edit) {
			var result = [];

			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = tableTindakanRadio.row(diff[i].node).data();
				result.push({
					"uid": rowData.uid,
					"position": (diff[i].newPosition + 1)
				});
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: {
					request: "update_position",
					jenis: "RJ",
					data: result
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response) {
					if (response.response_package > 0) {
						notification("success", "Kelas berhasil diurutkan", 3000, "kelas_tindakan_result");
					} else {
						notification("danger", "Kelas tindakan gagal diurutkan", 3000, "kelas_tindakan_result");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});






		var tableTindakanFis = $("#table-kelas-fis-tindakan").DataTable({
			ajax: {
				async: false,
				url: __HOSTAPI__ + "/Tindakan/kelas/FIS",
				type: "GET",
				headers: {
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc: function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			rowReorder: {
				dataSrc: "autonum"
			},
			columns: [{
					"data": "autonum",
					render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
							"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" cat=\"rj\"" +
							"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
							"</button>" +
							"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" cat=\"rj\">" +
							"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
							"</button>" +
							"</div>";
					}
				}
			]
		});

		tableTindakanFis.on('row-reorder', function(e, diff, edit) {
			var result = [];

			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = tableTindakanFis.row(diff[i].node).data();
				result.push({
					"uid": rowData.uid,
					"position": (diff[i].newPosition + 1)
				});
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: {
					request: "update_position",
					jenis: "RJ",
					data: result
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response) {
					if (response.response_package > 0) {
						notification("success", "Kelas berhasil diurutkan", 3000, "kelas_tindakan_result");
					} else {
						notification("danger", "Kelas tindakan gagal diurutkan", 3000, "kelas_tindakan_result");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});














		var tableTindakanRJ = $("#table-kelas-rj-tindakan").DataTable({
			ajax: {
				async: false,
				url: __HOSTAPI__ + "/Tindakan/kelas/RJ",
				type: "GET",
				headers: {
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc: function(response) {
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			/*aaSorting: [[0, "asc"]],
			columnDefs:[
				{"targets":0, "className":"dt-body-left"}
			],*/
			rowReorder: {
				dataSrc: "autonum"
			},
			columns: [{
					"data": "autonum",
					render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
							"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" cat=\"rj\"" +
							"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
							"</button>" +
							"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" cat=\"rj\">" +
							"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
							"</button>" +
							"</div>";
					}
				}
			]
		});

		tableTindakanRJ.on('row-reorder', function(e, diff, edit) {
			var result = [];

			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = tableTindakanRJ.row(diff[i].node).data();
				result.push({
					"uid": rowData.uid,
					"position": (diff[i].newPosition + 1)
				});
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: {
					request: "update_position",
					jenis: "RJ",
					data: result
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response) {
					if (response.response_package > 0) {
						//tableTindakanRJ.ajax.reload();
						notification("success", "Kelas berhasil diurutkan", 3000, "kelas_tindakan_result");
					} else {
						notification("danger", "Kelas tindakan gagal diurutkan", 3000, "kelas_tindakan_result");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		var tableTindakanRI = $("#table-kelas-ri-tindakan").DataTable({
			ajax: {
				async: false,
				url: __HOSTAPI__ + "/Tindakan/kelas/RI",
				type: "GET",
				headers: {
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc: function(response) {
					console.clear();
					console.log(response);
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			/*aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],*/
			rowReorder: {
				dataSrc: "autonum"
			},
			columns: [{
					"data": "autonum",
					render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\"><i class=\"fa fa-arrows-alt\"></i> " + row.autonum + "</h5>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data": null,
					render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
							"<button class=\"btn btn-info btn-sm btn-edit-tindakan\" id=\"tindakan_edit_" + row["uid"] + "\" cat=\"ri\"" +
							"<span><i class=\"fa fa-edit\"></i> Edit</span>" +
							"</button>" +
							"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" cat=\"ri\">" +
							"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
							"</button>" +
							"</div>";
					}
				}
			]
		});

		tableTindakanRI.on('row-reorder', function(e, diff, edit) {
			var result = [];

			for (var i = 0, ien = diff.length; i < ien; i++) {
				var rowData = tableTindakanRI.row(diff[i].node).data();
				result.push({
					"uid": rowData.uid,
					"position": (diff[i].newPosition + 1)
				});
			}

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Tindakan",
				data: {
					request: "update_position",
					jenis: "RI",
					data: result
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response) {
					if (response.response_package > 0) {
						notification("success", "Kelas berhasil diurutkan", 3000, "kelas_tindakan_result");
					} else {
						notification("danger", "Kelas tindakan gagal diurutkan", 3000, "kelas_tindakan_result");
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("body").on("click", ".btn-delete-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			cat = $(this).attr("cat");

			var conf = confirm("Hapus tindakan item?");
			if (conf) {
				$.ajax({
					url: __HOSTAPI__ + "/Tindakan/master_tindakan_kelas/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "DELETE",
					success: function(response) {
						if (response.response_package.response_result > 0) {
							if (cat == "ri") {
								tableTindakanRI.ajax.reload();
							} else if (cat == "rj") {
								tableTindakanRJ.ajax.reload();
							} else if (cat == "lab") {
								tableTindakanLab.ajax.reload();
							} else if (cat == "rad") {
								tableTindakanRadio.ajax.reload();
							} else if (cat == "fis") {
								tableTindakanFis.ajax.reload();
							} else {
								tableTindakanRJ.ajax.reload();
							}
							notification("success", "Kelas berhasil dihapus", 3000, "kelas_tindakan_result");
						} else {
							notification("success", "Kelas berhasil dihapus", 3000, "kelas_tindakan_result");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});


		$("body").on("click", ".btn-edit-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			cat = $(this).attr("cat");
			$("#modal-large-title").html("Edit Tindakan " + ((cat == "RI") ? "Rawat Inap" : "Rawat Jalan"));

			$("#txt_nama").val($("#nama_" + selectedUID).html());

			$("#form-tambah").modal("show");
			return false;
		});


		$(".tambah-tindakan").click(function() {
			cat = $(this).attr("cat");
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Tindakan " + ((cat == "RI") ? "Rawat Inap" : "Rawat Jalan"));
		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();

			if (nama != "") {
				var form_data = {};
				if (MODE == "tambah") {
					form_data = {
						"request": "tambah_tindakan",
						"nama": nama,
						"jenis": cat
					};
				} else {
					form_data = {
						"request": "edit_tindakan",
						"uid": selectedUID,
						"nama": nama,
						"jenis": cat
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Tindakan",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response) {
						console.log(response);
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						if (response.response_package.response_result > 0) {
							if (cat == "rj") {
								tableTindakanRJ.ajax.reload();
							} else if (cat == "lab") {
								tableTindakanLab.ajax.reload();
							} else if (cat == "rad") {
								tableTindakanRadio.ajax.reload();
							} else if (cat == "rad") {
								tableTindakanFis.ajax.reload();
							} else {
								tableTindakanRI.ajax.reload();
							}
							notification("success", "Kelas berhasil disimpan", 3000, "kelas_tindakan_result");
						} else {
							notification("danger", "Kelas tindakan gagal disimpan", 3000, "kelas_tindakan_result");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});


		//$(".harga").inputmask({alias: 'currency', rightAlign: false, placeholder: "0.00", prefix: "", autoGroup: false, digitsOptional: true});
	});

	//function for create digits
	$.fn.digits = function() {
		return this.each(function() {
			$(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
		})
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama">Nama Kelas :</label>
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