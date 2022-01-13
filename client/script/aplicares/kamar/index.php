<script type="text/javascript">
	$(function(){
		var dataObj = {};

		var MODE = "tambah";
		loadKelas();

		var tableRuangan = $("#table-ruangan").DataTable({
			"ajax":{
				/*url: __HOSTAPI__ + "/Aplicares/get-ruangan-terdaftar",*/
				url: __HOSTAPI__ + "/Aplicares/get-ruangan-terdaftar-bpjs",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var data = response.response_package;
					var autonum = 1;
					var returnData = [];
					for(var key in data) {
					    if(data[key] !== null && data[key] !== undefined) {
					        if(data[key].nama !== undefined) {
                                returnData.push({
                                    "nama": String(data[key].nama),
                                    "uid_ruangan": String(data[key].uid_ruangan),
                                    "autonum": autonum,
                                    "kode_ruangan": String(data[key].koderuang),
                                    "kodekelas": String(data[key].kodekelas),
                                    "kapasitas": String(data[key].kapasitas),
                                    "tersedia": String(data[key].tersedia),
                                    "tersediapria": String(data[key].tersediapria),
                                    "tersediawanita": String(data[key].tersediawanita),
                                    "tersediapriawanita": String(data[key].tersediapriawanita),
                                });
                                autonum++;
                            }
                        }
					}
					return returnData;
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
						return  "<span id=\"koderuangan_" + row["uid_ruangan"] + "\">" + row["kode_ruangan"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['nama'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kodekelas_" + row["uid_ruangan"] + "\">" + row["kodekelas"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"kapasitas_" + row["uid_ruangan"] + "\">" + row["kapasitas"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tersedia_" + row["uid_ruangan"] + "\">" + row["tersedia"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tersediapria_" + row["uid_ruangan"] + "\">" + row["tersediapria"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tersediawanita_" + row["uid_ruangan"] + "\">" + row["tersediawanita"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tersediapriawanita_" + row["uid_ruangan"] + "\">" + row["tersediapriawanita"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									/*"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
										"<i class=\"fa fa-list\"></i> Detail" +
									"</button>" +*/
									"<button id=\"ruangan_edit_" + row['uid_ruangan'] + "\" class=\"btn btn-info btn-sm btn-edit-ruangan\" data-toggle='tooltip' title='Edit'>" +
										"<span><i class=\"fa fa-edit\"></i>Edit</span>" +
									"</button>" +
									"<button id=\"ruangan_delete_" + row['uid_ruangan'] + "\" class=\"btn btn-danger btn-sm btn-delete-ruangan\" data-toggle='tooltip' title='Hapus'>" +
										"<span><i class=\"fa fa-trash\"></i>Hapus</span>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("#btnTambahRuangan").click(function(){
			var MODE = "tambah";
			loadRuangan(MODE);
			$("#ruangan").val("");
			$("#ruangan").trigger('change');
			$("#kodekelas").val("");
			$("#kodekelas").trigger('change');
			$("#kapasitas").val(0);
			$("#tersedia").val(0);
			$("#tersediapria").val(0);
			$("#tersediawanita").val(0);
			$("#tersediapriawanita").val(0);
			$("#form-tambah").modal("show");
		});


		$("#btnSubmit").click(function(){
			var allData = [];
			var uidRuangan = $("#ruangan").val();

			$("#form-tambah .inputan").each(function(){
				var value = $(this).val();

				if (value != "" && value != null){
					var name = $(this).attr("id");
					dataObj[name] = value;
				}
			});


			if(MODE == "tambah") {
				form_data = {
					request : "tambah-ruangan",
					uid_ruangan: uidRuangan,
					dataObj : dataObj
				};
			} else {
				form_data = {
					request : "edit-ruangan",
					uid_ruangan: uidRuangan,
					dataObj : dataObj
				};
			}


			if (dataObj !== undefined){
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Aplicares",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
					    console.clear();
						console.log(response);
						tableRuangan.ajax.reload();
						$("#form-tambah").modal("hide");
					},
					error: function(response) {
						console.log("Error : ");
						console.log(response);
					}
				});

			}
		});

		$(".select2").select2({
			dropdownParent: $('#form-tambah')
		});

		$(".select2_edit").select2({
			dropdownParent: $('#form-edit')
		});


		$("body").on("click", ".btn-delete-ruangan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus ruangan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Aplicares/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
                        console.clear();
                        console.log(response);
						tableRuangan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}

		});

		$("body").on("click", ".btn-edit-ruangan", function() {
			MODE = "edit";
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			loadRuangan(MODE, uid);
			var title = MODE[0].toUpperCase() + MODE.substring(1, MODE.length);

			$("#title-form").html(title);

			
			
			selectedUID = uid;

			$("#ruangan").val(uid);
			$("#ruangan").trigger('change');

			$("#kodekelas").val($("#kodekelas_" + uid).html());
			$("#kodekelas").trigger('change');

			$("#kapasitas").val($("#kapasitas_" + uid).html());
			$("#tersedia").val($("#tersedia_" + uid).html());
			$("#tersediapria").val($("#tersediapria_" + uid).html());
			$("#tersediawanita").val($("#tersediawanita_" + uid).html());
			$("#tersediapriawanita").val($("#tersediapriawanita_" + uid).html());

			$("#form-tambah").modal("show");
			return false;
		});

		/*

		$(".ruangan").select2({
			dropdownParent: $('#form-tambah'),
			minimumInputLength: 2,
			placeholder: 'Ketik kode atau nama Ruangan',
			ajax: {
				url: function (params) {
					var url = __HOSTAPI__ + "/Aplicares/get-ruangan/" + params.term;
					return url;
				},
				processResults: function (data, page) {
					console.log(data);
					return {
						results: data
					}
				}
			}
		});
		*/
	});

	function loadRuangan(params, selected = ""){

		var url = "";
		if (params == "tambah") {
			url = "get-ruangan";
			resetSelectBox("ruangan", "Ruangan");
			$("#ruangan").removeAttr("disabled");
			$("#kodekelas").removeAttr("disabled");
		} else if (params == "edit"){
			var ruang = $("#ruangan").val();
			url = "get-ruangan-terdaftar";
			$("#ruangan option").remove();
			$("#ruangan").attr("disabled",true);
			$("#kodekelas").attr("disabled",true);
		}

		$.ajax({
			url: __HOSTAPI__ + "/Aplicares/" + url,
			type: "GET",
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			success: function(response){
				var MetaData = response.response_package.response_data;

				for(i = 0; i < MetaData.length; i++){
					var selection = document.createElement("OPTION");

					$(selection).attr("value", MetaData[i].uid).html(MetaData[i].kode_ruangan +" - "+ MetaData[i].nama);
					if(MetaData[i].uid == selected) {
						$(selection).attr("selected", "selected");
					}
					$("#ruangan").append(selection);
				}
			},
			error: function(response) {
				console.log(response);
			}
		});
	}

	function loadKelas(){
		resetSelectBox("kodekelas", "Kelas");

		$.ajax({
			url:__HOSTAPI__ + "/Aplicares/get-kelas-kamar",
			type: "GET",
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			success: function(response){
			    //console.log(response);
				var MetaData = response.response_package;
				if (MetaData !== null && MetaData.length > 0){
					 for(i = 0; i < MetaData.length; i++){
						var selection = document.createElement("OPTION");

						$(selection).attr("value", MetaData[i].kodekelas).html(MetaData[i].namakelas);
						$("#kodekelas").append(selection);
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

<div id="form-tambah" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title"><span id="title-form"></span> Ruangan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12">
						<label for="ruangan">Ruangan:</label>
						<select class="form-control ruangan select2" id="ruangan"></select>
					</div>
					<div class="form-group col-md-6">
						<label for="kodekelas">Kelas:</label>
						<select class="form-control select2 inputan kodekelas" id="kodekelas">
							<option value="">Pilih Kelas</option>
						</select>
					</div>
					<div class="form-group col-md-6">
						<label for="kapasitas">Kapasitas:</label>
						<div class="input-group input-group-merge">
							<input type="number" autocomplete="off" class="form-control form-control-appended inputan" id="kapasitas" value="0" />
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="kapasitas">Tersedia:</label>
						<div class="input-group input-group-merge">
							<input type="number" autocomplete="off" class="form-control form-control-appended inputan" id="tersedia" value="0" />
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="tersediapria">Tersedia Pria:</label>
						<div class="input-group input-group-merge">
							<input type="number" autocomplete="off" class="form-control form-control-appended inputan" id="tersediapria"value="0"/>
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="tersediawanita">Tersedia Wanita:</label>
						<div class="input-group input-group-merge">
							<input type="number" autocomplete="off" class="form-control form-control-appended inputan" id="tersediawanita" value="0"/>
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="tersediapriawanita">Tersedia Pria dan Wanita:</label>
						<div class="input-group input-group-merge">
							<input type="number" autocomplete="off" class="form-control form-control-appended inputan" id="tersediapriawanita" value="0" />
							<div class="input-group-append">
								<div class="input-group-text">
									orang
								</div>
							</div>
						</div>
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