<script type="text/javascript">
	$(function(){
		loadKategori();
		var MODE = "tambah", selectedUID;
		var tableSubkategori = $("#table-subkategori").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Arsip/subkategori",
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
						return "<span id=\"kategori_" + row["id"] + "\" kategori=\""+row['id_kategori']+"\">" + row["nama_kategori"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["id"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-subkategori\" id=\"subkategori_edit_" + row["id"] + "\">" +
										"<i class=\"fa fa-pencil\"></i> Edit" +
									"</button>" +
									"<button id=\"subkategori_delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-subkategori\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-subkategori", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus Subkategori Arsip?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Arsip/arsip_subkategori/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						console.log('Berhasil : '+response);
						tableSubkategori.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$("body").on("click", ".btn-edit-subkategori", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			var kategori = $("#kategori_" + uid).attr("kategori");
			loadKategori(kategori);
			$("#kategori").trigger('change');
			$("#form-tambah").modal("show");
			$("#modal-large-title").html("Edit Subkategori");
			return false;
		});

		$("#tambah-subkategori").click(function() {

			$("#form-tambah").modal("show");
			MODE = "tambah";
			$("#modal-large-title").html("Tambah Subkategori");

		});

		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			var kategori = $("#kategori").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "add_subkategori",
						"nama": nama,
						"kategori":kategori
					};
				} else {
					form_data = {
						"request": "edit_subkategori",
						"id": selectedUID,
						"nama": nama,
						"kategori":kategori
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Arsip/subkategori",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#txt_nama").val("");
						$("#kategori").val("");
						$("#kategori").trigger('change');
						$("#form-tambah").modal("hide");
						tableSubkategori.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		$(".kategori").select2({
    		dropdownParent: $("#form-tambah")
		});

	});

	function loadKategori(selected=''){
        $.ajax({
            url:__HOSTAPI__ + "/Arsip/kategori",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;
				$("#kategori option").remove();
                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
					if(selected==MetaData[i].id){
						$(selection).attr('selected','selected');
					}
                    $("#kategori").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Subkategori</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<label for="kategori">Kategori:</label>
					<select class="form-control kategori" id="kategori">
						<option value="" disabled selected>Pilih Kategori</option>
					</select>
				</div>
				<div class="form-group col-md-12">
					<label for="txt_no_skp">Nama Subkategori:</label>
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