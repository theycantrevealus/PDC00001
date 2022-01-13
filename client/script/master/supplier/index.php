<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var tableSupplier = $("#table-supplier").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Supplier",
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
						var alamat = "";
						if(row.kontak == undefined) {
							alamat = "<span class=\"text-danger\">[ADDRESS NOT SET]</span>";
						} else {
							alamat = "<small>" +
										"<i class=\"fa fa-home\"></i> " + row.alamat +
									"</small>";
						}
						return 	"<b class=\"title-caption text-info\" id=\"nama_" + row.uid + "\">" + row.nama + "</b><br />" +
								alamat;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var kontak = "";
						if(row.kontak == undefined) {
							kontak = "<span class=\"text-danger\">[CONTACT NOT SET]</span>";
						} else {
							kontak = "<i class=\"fa fa-phone\"></i> +62" + row["kontak"];
						}

						var email = "";
						if(row.email == undefined) {
							email = "<span class=\"text-danger\">[EMAIL NOT SET]</span>";
						} else {
							email = "<a href=\"mailto:" + row.email + "\"><i class=\"fa fa-envelope\"></i> " + row.email + "</a>";
						}

						return email + "<br />" + kontak;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var relasi_po = "";
						if(row.relasi_po.length > 0) {
							relasi_po = "<span class=\"text-success\"><i class=\"fa fa-check-square\"></i> " + row.relasi_po.length + " linked</span>";
						} else {
							relasi_po = "<span class=\"text-danger\">No Link</span>";
						}
						return relasi_po;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/master/supplier/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-supplier\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</a>" +
									"<button id=\"supplier_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-supplier\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-supplier", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus supplier item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Supplier/master_supplier/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						if(response.response_package.response_result > 0) {
							notification ("success", "Supplier berhasil dihapus", 3000, "hasil_supplier");
							tableSupplier.ajax.reload();
						} else {
							notification ("danger", "Supplier gagal dihapus", 3000, "hasil_supplier");
						}		
					},
					error: function(response) {
						notification ("danger", "Supplier gagal dihapus", 3000, "hasil_supplier");
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-supplier", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			return false;
		});

		$("#tambah-supplier").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_supplier",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_supplier",
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
						tableSupplier.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>
