<script type="text/javascript">
	$(function(){
		var selectedID;
		loadJenisTindakan();
		
		var tableLayanan = $("#table-layanan-radiologi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Radiologi/tindakan",
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
						return row["nama"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['jenis'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
								"<button data-uid='" + row['uid'] + "' data-jenis='"+ row['uid_jenis'] +"' data-nama='"+ row['nama'] +"'class=\"btn btn-warning btn-sm btnEdit\" data-toggle='tooltip' title='Edit'><i class=\"fa fa-edit\"></i></button>" +

								"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" data-toggle='tooltip' title='Hapus'>" +
									"<i class=\"fa fa-trash\"></i>" +
								"</button>" +
							"</div>";
					}
				}
			]
		});

		$("#table-layanan-radiologi tbody").on("click", ".btn-delete-tindakan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Radiologi/master_tindakan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						//console.log(response);
						tableLayanan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
		
		$("#btnTambahData").click(function() {
			$("#nama").val("");
			$("#jenis").val("").trigger('change');
			$("#form-tambah").modal("show");
			MODE = "tambah";
		});

		$("#table-layanan-radiologi tbody").on("click", ".btnEdit", function() {
			selectedID = $(this).data("uid");
			let jenis = $(this).data("jenis");
			let nama = $(this).data("nama");

			MODE = "edit";
			$("#nama").val(nama);
			$("#jenis").val(jenis).trigger('change');
			$("#form-tambah").modal("show");

			return false;
		});

		$("#btnSubmit").click(function() {
			var nama = $("#nama").val();
			var jenis = $("#jenis").val();

			if(nama != "" && jenis != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah-tindakan",
						"nama": nama,
						"jenis": jenis
					};
				} else {
					form_data = {
						"request": "edit-tindakan",
						"uid": selectedID,
						"jenis": jenis,
						"nama": nama
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Radiologi",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#nama").val("");
						$("#jenis").val("");
						$("#form-tambah").modal("hide");
						tableLayanan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
	});
	
	function loadJenisTindakan(){
		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Radiologi/jenis",
			type: "GET",
			beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#jenis").append(selection);
	                }
					
					$("#jenis").select2({
						dropdownParent: $('#form-tambah')
					});
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
				<h5 class="modal-title" id="modal-large-title">Tambah Tindakan Radiologi</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-12 col-md-12 mb-3 form-group">
					<label for="">Nama Layanan / Tindakan:</label>
					<input type="text" name="nama" id="nama" class="form-control">
				</div>
					<div class="col-12 col-md-12 mb-3 form-group">
					<label for="">Jenis Layanan:</label>
					<select class="form-control" id="jenis" nama="jenis">
						<option value="">Pilih</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btnSubmit">Submit</button>
			</div>
		</div>
	</div>
</div>