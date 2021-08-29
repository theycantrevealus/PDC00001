<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var dataLibrary = {};
		if(__MY_PRIVILEGES__.response_data[0].uid === __UIDPETUGASGUDANGFARMASI__) {
		    $("#txt_jenis option[value=\"RAD\"]").remove();
            $("#txt_jenis option[value=\"LAB\"]").remove();
        } else {
            $("#txt_jenis option[value=\"FAR\"]").remove();
        }
		var tableMitra = $("#table-mitra").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Mitra",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var data = response.response_package.response_data;
					var parsedData = [];
					for(var key in data) {
                        if(data[key].jenis !== "GEN") {
                            parsedData.push(data[key]);

                            if(dataLibrary[data[key].uid] !== undefined) {
                                dataLibrary[data[key].uid] = data[key];
                            }

                            dataLibrary[data[key].uid] = data[key];
                        }
					}

					return parsedData;
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
						return "<b id=\"nama_" + row.uid + "\">" + row.nama + "</b><br />" + row.kontak + "<br />" + row.alamat;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						var type = {
							RAD: "Radiologi",
							LAB: "Laboratorium",
                            FAR: "Farmasi"
						}
						return "<span id=\"jenis_" + row.uid + "\">" + type[row.jenis] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-mitra\" id=\"mitra_edit_" + row.uid + "\">" +
										"<span>\<i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"mitra_delete_" + row.uid + "\" class=\"btn btn-danger btn-sm btn-delete-mitra\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-mitra", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus mitra?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Mitra/master_mitra/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableMitra.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$('#txt_kontak').inputmask("9999999999999");

		$("body").on("click", ".btn-edit-mitra", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#txt_jenis").val(dataLibrary[uid].jenis).trigger("change");
			$("#txt_kontak").val(dataLibrary[uid].kontak);
			$("#txt_alamat").val(dataLibrary[uid].alamat);
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Mitra");
			return false;
		});

		$("#tambah-mitra").click(function() {

			$("#form-tambah").modal("show");
			$("#txt_nama").val("");
			$("#txt_jenis").val("none").trigger("change");
			$("#txt_kontak").val("");
			$("#txt_alamat").val("");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Mitra");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var jenis = $("#txt_jenis").val();
			var kontak = $("#txt_kontak").inputmask("unmaskedvalue");
			/*if(kontak.length < 13) {
			    kontak.substr(1, (kontak.length - 1));
            }
			alert(kontak);*/
			var alamat = $("#txt_alamat").val();

			if(nama != "" && jenis != "none") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_mitra",
						"nama": nama,
						"jenis": jenis,
						"kontak": kontak,
						"alamat": alamat
					};
				} else {
					form_data = {
						"request": "edit_mitra",
						"uid": selectedUID,
						"nama": nama,
						"jenis": jenis,
						"kontak": kontak,
						"alamat": alamat
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Mitra",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						if(response.response_package.response_result > 0) {
							if(MODE == "tambah") {
								notification ("success", "Mitra berhasil ditambah", 3000, "hasil_mitra");	
							} else {
								notification ("success", "Mitra berhasil diedit", 3000, "hasil_mitra");
							}

							$("#txt_nama").val("");
							$("#txt_kontak").val("");
							$("#txt_alamat").val("");
							$("#form-tambah").modal("hide");
							tableMitra.ajax.reload();
						} else {
							notification ("danger", "Mitra gagal diproses", 3000, "hasil_mitra");
						}
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
		$("#txt_jenis").select2();
	});
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Mitra</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="txt_nama">Nama Mitra:</label>
					<input type="text" class="form-control" placeholder="Nama Mitra" id="txt_nama" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_jenis">Kemitraan:</label>
					<select class="form-control" id="txt_jenis">
						<option value="none">Pilih Jenis Kemitraan</option>
						<option value="LAB">Laboratorium</option>
						<option value="RAD">Radiologi</option>
                        <option value="FAR">Farmasi</option>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label for="txt_kontak">Kontak Mitra:</label>
					<input type="text" class="form-control" id="txt_kontak" placeholder="____" />
				</div>
				<div class="form-group col-md-12">
					<label for="txt_alamat">Alamat Mitra:</label>
					<textarea class="form-control" id="txt_alamat" placeholder="Alamat mitra"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>