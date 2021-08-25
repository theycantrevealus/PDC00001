<script type="text/javascript">
	$(function(){
		loadLantai();

		$(".select2").select2({});

		var MODE = "tambah", selectedUID;

		var groupColumn = 2;
		var tableRuangan = $("#table-ruangan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Ruangan/ruangan",
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
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kode_" + row["uid"] + "\">" + row["kode_ruangan"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kelas_" + row["uid"] + "\" data-id=\""+ row['id_kelas'] +"\">" + row["kelas"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kapasitas_" + row["uid"] +"\">" + row["kapasitas"] + "</span> orang";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"lantai_" + row["uid"] + "\" data-uid=\""+ row['uid_lantai'] +"\">" + row["lantai"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<button class=\"btn btn-info btn-sm btn-edit-ruangan\" id=\"ruangan_edit_" + row["uid"] + "\">" +
										"<span><i class=\"fa fa-pencil-alt\"></i> Edit</span>" +
									"</button>" +
									"<button id=\"ruangan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-ruangan\">" +
										"<span><i class=\"fa fa-trash\"></i> Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			],
		});

		loadTermSelectBox("kelas", 14, "Kelas");

		$("body").on("click", ".btn-delete-ruangan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus ruangan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Ruangan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tableRuangan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		
		$("body").on("click", ".btn-edit-ruangan", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#nama").val($("#nama_" + uid).html());
			$("#kode_ruangan").val($("#kode_"+ uid).html());
			$("#kapasitas").val($("#kapasitas_" + uid).html());

			var kelas = $("#kelas_" + uid).data('id');
			selectedKelas = kelas;
			$("#kelas").val(selectedKelas);
			$("#kelas").trigger('change');

			var lantai = $("#lantai_" + uid).data('uid').split('_');
			lantai = lantai[lantai.length - 1];
			selectedLantai = lantai;

			$("#lantai").val(lantai);
			$("#lantai").trigger('change');

			$("#form-tambah").modal("show");
			return false;
		});

		
		$("#tambah-ruangan").click(function() {
			$("#nama").val("");
			$("#kode_ruangan").val("");
			$("#kapasitas").val("");
			$("#lantai").val("");
			$("#lantai").trigger('change');

			$("#form-tambah").modal("show");
			MODE = "tambah";

		});


		$("#btnSubmit").click(function() {
			var nama = $("#nama").val();
			var kode_ruangan = $("#kode_ruangan").val();
			var kelas = $("#kelas").val();
			var kapasitas = $("#kapasitas").val();
			var lantai = $("#lantai").val();

			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_ruangan",
						"nama": nama,
						"kode_ruangan": kode_ruangan,
						"kelas": kelas,
						"kapasitas": kapasitas,
						"lantai": lantai
					};
				} else {
					form_data = {
						"request": "edit_ruangan",
						"uid": selectedUID,
						"nama": nama,
						"kode_ruangan": kode_ruangan,
						"kelas": kelas,
						"kapasitas": kapasitas,
						"lantai": lantai
					};
				}

				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Ruangan",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						console.log(response);
						$("#nama").val("");
						$("#kode_ruangan").val("");
						$("#kelas").val("");
						$("#kapasitas").val("");
						$("#lantai").val("");
						//$("#lantai").trigger('change');
						$("#form-tambah").modal("hide");
						tableRuangan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});

	function loadLantai(){
        $.ajax({
            url:__HOSTAPI__ + "/Lantai/lantai",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                for(i = 0; i < MetaData.length; i++){
                    var selection = document.createElement("OPTION");

                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
                    $("#lantai").append(selection);
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

	function loadTermSelectBox(selector, id_term, nama){
		resetSelectBox(selector, nama);

		$.ajax({
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id_term,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].id).html(MetaData[i].nama);
	                    $("#" + selector).append(selection);
	                }
                }
                
            },
            error: function(response) {
                console.log(response);
            }
        });
	}

	function resetSelectBox(selector, name){
		$("#"+ selector +" option").remove();
		var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
	}
</script>

<div id="form-tambah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md bg-danger" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Ruangan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12">
						<label for="nama">Nama Ruangan:</label>
						<input type="text" autocomplete="off" class="form-control" id="nama" />
					</div>
					<div class="form-group col-md-4">
						<label for="kode_ruangan">Kode Ruangan:</label>
						<input type="text" autocomplete="off" class="form-control" id="kode_ruangan" />
					</div>
					<div class="form-group col-md-4">
						<label for="kelas">Kelas:</label>
						<select class="form-control select2" id="kelas">
							<option value="">Pilih Kelas</option>
						</select>
					</div>
						<div class="form-group col-md-4">
						<label for="kapasitas">Kapasitas:</label>
						<div class="input-group input-group-merge">
							<input type="text" autocomplete="off" class="form-control form-control-appended" id="kapasitas" />
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-group col-md-12">
						<label for="lantai">Lantai:</label>
						<select class="form-control select2" id="lantai">
							<option value="" disabled selected>Pilih Lantai</option>
						</select>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="btnSubmit">Submit</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>