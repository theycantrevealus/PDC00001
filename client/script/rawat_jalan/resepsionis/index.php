<script type="text/javascript">
	$(function(){
		var params;
		var MODE = false;

        /*var dataFaskes = bpjs_load_faskes();
        for(var faskesKey in dataFaskes) {
            $("#txt_bpjs_faskes").append("<option></option>");
        }*/

		var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Antrian/antrian",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					console.log(response.response_package.response_data);
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			"bInfo" : false,
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
						return row["waktu_masuk"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["no_rm"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["pasien"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["departemen"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["dokter"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						if(row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
							if(parseInt(row['sep']) > 0) {
								return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row["sep"] + "</h6>";
							} else {
								return row["penjamin"] + " <button class=\"btn btn-info btn-sm daftar_sep\" id=\"" + row["uid_pasien"] + "\"><i class=\"fa fa-plus\"></i> Daftar SEP</button>";
							}
						} else {
							return row["penjamin"];
						}
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},
				/*{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"penjamin_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-antrian\">" +
										"<i class=\"fa fa-trash\"></i>" +
									"</button>" +
								"</div>";
					}
				}*/
			]
		});

		var targettedPasienSEP = "";

		$("body").on("click", ".daftar_sep", function() {
			var id = $(this).attr("id");
			targettedPasienSEP = id;
			if(targettedPasienSEP != "" && targettedPasienSEP != undefined) {
				$("#modal-sep").modal("show");
			}
		});


		/*================== FORM CARI AREA ====================*/

		$('#table-list-pencarian').DataTable({
			"bFilter": false,
			"bInfo" : false
		});

		$("#txt_cari").on('keyup', function(){
			params = $("#txt_cari").val();

			$("#table-list-pencarian tbody").html("");
			$("#pencarian-notif").attr("hidden",true);
			$("#loader-search").removeAttr("hidden");
			if (params != ""){
				setTimeout(function(){
					$.ajax({
						async: false,
						url:__HOSTAPI__ + "/Antrian/cari-pasien/" + params,
						type: "GET",
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						success: function(response){
							var MetaData = dataTindakan = response.response_package.response_data;

							var html = "";
							if (MetaData != ""){
								$.each(MetaData, function(key, item){
									var nik = item.nik;
									if (nik == null){
										nik = '-';
									}

									var buttonAksi = "<td style='text-align:center;'><button id=\"btn_daftar_pasient_" + item.uid + "\" class=\"btn btn-sm btn-info btnDaftarPasien\" data-toggle=\"tooltip\" title=\"Tambah ke Antrian\"><i class=\"fa fa-user-plus\"></i></button></td>";

									if (item.berobat == true){
										buttonAksi = "<td clsas=\"wrap_content\" style=\"text-align:center;\"><span class=\"badge badge-warning\">Sedang Berobat</span></td>";
									}

									html += "<tr disabled>" +
												"<td class=\"wrap_content\">"+ item.autonum  +"</td>" +
												"<td class=\"wrap_content\">"+ item.no_rm +"</td>" +
												"<td>"+ item.nama +"</td>" +
												"<td>"+ nik +"</td>" +
												"<td class=\"wrap_content\">"+ item.jenkel +"</td>" +
												buttonAksi +
											"</tr>";
								});
							} else {
								html += "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
							}
							
							$("#table-list-pencarian tbody").html(html);
							$("#loader-search").attr("hidden",true);
						},
						error: function(response) {
							console.log(response);
						}
					});
					
				}, 250);
			} else {
				$("#loader-search").attr("hidden",true);

				var html = "<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>";
				$("#table-list-pencarian tbody").html(html);
			}
			
			$("#btnTambahPasien").fadeIn("fast");
		});

		$("#btnTambahPasien").click(function(){
			localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
		});

		$("#btnTambahAntrian").click(function(){
			var currentAntrian = $("#txt_current_antrian").attr("current_queue");
			if(currentAntrian == undefined || currentAntrian == null) {
				alert("Tidak ada antrian");
			} else {
				$("#btnTambahPasien").fadeOut("false");
				$("#txt_cari").val("");
				$("#table-list-pencarian tbody").html("<tr><td colspan='6' align='center'>Tidak Ada Data</td></tr>");
				$("#modal-cari").modal("show");
			}
		});

		$("body").on("click", ".btnDaftarPasien", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			localStorage.setItem("currentPasien", uid);
			localStorage.setItem("currentAntrianID", $("#txt_current_antrian").attr("current_queue"));
			location.href = __HOSTNAME__ + "/rawat_jalan/resepsionis/tambah/" + uid;
		});


		//SOCKET
		Sync.onmessage = function(evt) {
			var signalData = JSON.parse(evt.data);
			var command = signalData.protocols;
			var type = signalData.type;
			var sender = signalData.sender;
			var receiver = signalData.receiver;
			var time = signalData.time;
			var parameter = signalData.parameter;

			if(command !== undefined && command !== null && command !== "") {
				protocolLib[command](command, type, parameter, sender, receiver, time);
			}
		}



		var protocolLib = {
			userlist: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			userlogin: function(protocols, type, parameter, sender, receiver, time) {
				//
			},
			anjungan_kunjungan_baru: function(protocols, type, parameter, sender, receiver, time) {
				refresh_notification();
				reinitAntrianSync($("#txt_loket").val());
			},
			anjungan_kunjungan_panggil: function(protocols, type, parameter, sender, receiver, time) {
				//
			}
		};

		//INIT
		
		function reinitAntrianSync(argument) {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/check_job/" + argument,
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					var dataCheck = response.response_package;
					$("#sisa_antrian").html(dataCheck.response_standby);
					if(dataCheck.response_data.length > 0) {
						//Belum terproses
						$("#btnGunakanLoket").attr("disabled", "disabled");
						$("#btnSelesaiGunakan").removeAttr("disabled");
						$("#txt_loket")
							.append("<option value=\"" + dataCheck.response_data[0].loket.uid + "\">" + dataCheck.response_data[0].loket.nama_loket + "</option>")
							.attr("disabled", "disabled");
						$("#txt_loket").select2();
						$("#txt_current_antrian").html(dataCheck.response_queue).attr({
							"current_queue": dataCheck.response_queue_id
						});
					} else {
						if(dataCheck.response_used != undefined && dataCheck.response_used != "") {
							load_loket("#txt_loket", dataCheck.response_used);
							$("#txt_loket").attr("disabled", "disabled");
							$("#btnSelesaiGunakan").removeAttr("disabled", "disabled");
							$("#btnGunakanLoket").attr("disabled", "disabled");
							reloadPanggilan($("#txt_loket").val(), dataCheck.response_queue_id);
							//Otomatis Panggil
							//reloadPanggilan($("#txt_loket").val());
						} else {
							load_loket("#txt_loket");
							$("#btnNext").attr("disabled", "disabled");
							$("#btnTambahAntrian").attr("disabled", "disabled");
						}
						$("#txt_loket").select2();
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		}

		reinitAntrianSync($("#txt_loket").val());
			





		function load_loket(target, selected = "") {
			//
			var loketData;
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan/avail_loket",
				type: "GET",
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					loketData = response.response_package.response_data;
					$(target).find("option").remove();
					for(var a = 0; a < loketData.length; a++) {
						$(target).append("<option " + (loketData[a].uid == selected ? "selected=\"selected\"" : "") + " value=\"" + loketData[a].uid + "\">" + loketData[a].nama_loket + "</option>")
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return loketData;
		}

		function reloadPanggilan(loket, currentQueue = "") {
			if(currentQueue != "") {
				dataForm = {
					request:"next_antrian",
					loket:loket,
					currentQueue: currentQueue
				}
			} else {
				dataForm = {
					request:"next_antrian",
					loket:loket
				}
			}
			var currentQueue;
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:dataForm,
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					if(response.response_package !== undefined && response.response_package !== null) {
						currentQueue = response.response_package;
						$("#sisa_antrian").html(currentQueue.response_standby);
						if((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null || currentQueue.response_queue == 0)) {
							//reloadPanggilan(loket, "");
						} else {
							
							$("#txt_current_antrian").html((currentQueue.response_queue == "" || currentQueue.response_queue == undefined || currentQueue.response_queue == null) ? "0" : currentQueue.response_queue).attr({
								"current_queue": currentQueue.response_queue_id
							});
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
			return currentQueue;
		}

		$("#btnGunakanLoket").click(function() {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:{
					request:"ambil_antrian",
					loket:$("#txt_loket").val()
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					if(response.response_package.response_result > 0) {
						notification ("success", "Loket berhasil digunakan", 3000, "hasil_loket");
						$("#btnGunakanLoket").attr("disabled", "disabled");
						reloadPanggilan($("#txt_loket").val());
						$("#txt_loket").attr("disabled", "disabled");
						$("#btnSelesaiGunakan").removeAttr("disabled");	
						$("#btnNext").removeAttr("disabled", "disabled");
						$("#btnTambahAntrian").removeAttr("disabled");
					} else {
						if(response.response_package.response_loket_user == __ME__) {
							notification ("success", "Loket berhasil digunakan", 3000, "hasil_loket");
							$("#btnGunakanLoket").attr("disabled", "disabled");
							reloadPanggilan($("#txt_loket").val());
							$("#txt_loket").attr("disabled", "disabled");
							$("#btnSelesaiGunakan").removeAttr("disabled");	
							$("#btnNext").removeAttr("disabled", "disabled");
							$("#btnTambahAntrian").removeAttr("disabled");
						} else {
							notification ("info", "Loket sudah digunakan " + response.response_package.response_loket, 3000, "hasil_loket");
						}
					}
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnSelesaiGunakan").click(function() {
			$.ajax({
				async: false,
				url:__HOSTAPI__ + "/Anjungan",
				type: "POST",
				data:{
					request:"selesai_antrian"
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				success: function(response){
					/*if(response.response_package.response_result > 0) {
						load_loket("#txt_loket");
						notification ("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
						$("#txt_current_antrian").html("0");
						$("#btnGunakanLoket").removeAttr("disabled");
						$("#txt_loket").removeAttr("disabled");
						$("#btnSelesaiGunakan").attr("disabled", "disabled");
						$("#btnNext").attr("disabled", "disabled");
						$("#btnTambahAntrian").attr("disabled", "disabled");
					} else {
						notification ("warning", "Anda telah keluar loket", 3000, "hasil_loket");
					}*/
					load_loket("#txt_loket");
					notification ("success", "Berhasil keluar dari loket", 3000, "hasil_loket");
					$("#txt_current_antrian").html("0");
					$("#btnGunakanLoket").removeAttr("disabled");
					$("#txt_loket").removeAttr("disabled");
					$("#btnSelesaiGunakan").attr("disabled", "disabled");
					$("#btnNext").attr("disabled", "disabled");
					$("#btnTambahAntrian").attr("disabled", "disabled");
				},
				error: function(response) {
					console.log(response);
				}
			});
		});

		$("#btnNext").click(function() {
			reloadPanggilan($("#txt_loket").val(), $("#txt_current_antrian").attr("current_queue"));
		});
		$("#btnPanggil").click(function() {
			push_socket($("#txt_loket").val(), "anjungan_kunjungan_panggil", "display_machine", {
				loket: $("#txt_loket").val(),
				nomor: $("#txt_current_antrian").html()
			}, "info");
		});
	});

</script>

<script src="<?= __HOSTNAME__ ?>/template/assets/vendor/toastr.min.js"></script>
<script src="<?= __HOSTNAME__ ?>/template/assets/js/toastr.js"></script>

<div id="modal-cari" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Tambah Antrian</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-6">
					<div class="col-md-6">
						<div class="row">
							<label for="txt_cari">Cari Pasien</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="search-form search-form--light input-group-lg col-md-10">
								<input type="text" class="form-control" placeholder="Nama / NIK / No. RM" id="txt_cari">
							</div>
							<div class="col-md-12" hidden id="pencarian-notif" style="color: red; font-size: 0.8rem;">
								Mohon ketikkan kata kunci pencarian
							</div>
							<div class="col-md-2">
								<div class="loader loader-lg loader-primary" id="loader-search" hidden></div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group col-md-12" >
					<!-- style="height: 100px; overflow: scroll;" -->
					<table class="table table-bordered table-striped" id="table-list-pencarian">
						<thead class="thead-dark">
							<tr>
								<th class="wrap_content">No</th>
								<th>No. RM</th>
								<th>Nama</th>
								<th>NIK</th>
								<th class="wrap_content">Jenis Kelamin</th>
								<th class="wrap_content">Aksi</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
				
			</div>
			<div class="modal-footer">
				<!-- <div id="spanBtnTambahPasien" hidden> -->
				<a href="<?= __HOSTNAME__ ?>/pasien/tambah?antrian=true" class="btn btn-success" id="btnTambahPasien">
				<!-- <i class="fa fa-plus"></i>  -->Tambah Pasien Baru
				</a>
				<!-- </div> -->
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div> 
	</div> 
</div>



<div id="modal-sep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Daftar SEP</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<div class="form-row">
						<div class="col-12 col-md-4 mb-7 form-group">
							<label for="">No Kartu</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
						</div>
						<div class="col-12 col-md-6 mb-6 form-group">
							<label for="">NIK Pasien</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Nama Pasien</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Tanggal SEP</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Faskes</label>
							<select class="form-control" id="txt_bpjs_faskes">
							</select>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Jenis Pelayanan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Kelas Rawat</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Nomor Medical Record (MR)</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Asal Rujukan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Tanggal Rujukan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Nomor Rujukan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Catatan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Diagnosa Awal</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
						<div class="col-12 col-md-8 mb-4 form-group">
							<label for="">Poli Tujuan</label>
							<input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!-- <div id="spanBtnTambahPasien" hidden> -->
				<a href="<?= __HOSTNAME__ ?>/pasien/tambah?antrian=true" class="btn btn-success" id="btnTambahPasien">
				<!-- <i class="fa fa-plus"></i>  -->Tambah Pasien Baru
				</a>
				<!-- </div> -->
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div> 
	</div> 
</div>