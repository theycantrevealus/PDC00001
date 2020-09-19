<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;

		var tableGudang = $("#table-item").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
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
						var kategoriObat = "";
						for(var kategoriObatKey in row["kategori_obat"]) {
							kategoriObat += "<span style=\"margin: 5px;\" class=\"badge badge-info\">" + row["kategori_obat"][kategoriObatKey].kategori + "</span>";
						}
						return 		"<div class=\"row\">" +
										"<div class=\"col-md-2\">" +
											"<img style=\"border-radius: 5px;\" src=\"" + __HOST__ + "/images/produk/" + row["uid"] + ".png\" width=\"60\" height=\"60\" />" +
										"</div>" +
										"<div class=\"col-md-10\">" +
											"<b><i>" + row["kode_barang"].toUpperCase() + "</i></b><br />" +
											"<h5>" + row["nama"].toUpperCase() + "</h5>" +
											kategoriObat +
										"</div>" +
									"</div>";
					}
				},
				/*{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["kode_barang"].toUpperCase() + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"].toUpperCase() + "</span>";
					}
				},*/
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["kategori"].nama.toUpperCase() + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["manufacture"].nama + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/master/inventori/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm\">" +
										"<i class=\"fa fa-pencil\"></i> Edit" +
									"</a>" +
									"<button id=\"gudang_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-gudang\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-gudang", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus gudang item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Inventori/master_inv_gudang/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableGudang.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-gudang", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Gudang");
			return false;
		});

		$("#tambah-gudang").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Gudang");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_gudang",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_gudang",
						"uid": selectedUID,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Inventori",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tableGudang.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>