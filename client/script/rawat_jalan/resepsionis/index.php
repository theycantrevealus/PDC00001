<script type="text/javascript">
	$(function(){
		var params;
		var MODE = false;
        $(".sep").select2();
        $("#txt_bpjs_tanggal_rujukan").datepicker({
            dateFormat: 'DD, dd MM yy',
            autoclose: true
        }).datepicker("setDate", new Date());

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
						return "<span id=\"rm_" + row.uid_pasien + "\">" + row.no_rm + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row.uid_pasien + "\">" + row["pasien"] + "<span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"poli_" + row.uid_pasien + "\">" + row["departemen"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.dokter;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						if(row["uid_penjamin"] == __UIDPENJAMINBPJS__) {
							if(parseInt(row['sep']) > 0) {
								return row["penjamin"] + " <h6 class=\"nomor_sep\">" + row.sep + "</h6>";
							} else {
								return row["penjamin"] + " <button antrian=\"" + row.uid + "\" allow_sep=\"" + ((row.waktu_keluar !== undefined) ? "1" : "0") + "\" class=\"btn btn-info btn-sm daftar_sep pull-right\" id=\"" + row.uid_pasien + "\"><i class=\"fa fa-plus\"></i> Daftar SEP</button>";
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

        loadKelasRawat();


        $("#txt_bpjs_asal_rujukan").select2({
            minimumInputLength: 2,
            "language": {
                "noResults": function(){
                    return "Faskes tidak ditemukan";
                }
            },
            dropdownParent: $("#modal-sep-new"),
            ajax: {
                dataType: "json",
                headers:{
                    "Authorization" : "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>,
                    "Content-Type" : "application/json",
                },
                url:__HOSTAPI__ + "/BPJS/get_faskes_select2/",
                type: "GET",
                data: function (term) {
                    return {
                        search:term.term,
                        type:$("#txt_bpjs_jenis_asal_rujukan").val()
                    };
                },
                cache: true,
                processResults: function (response) {
                    var data = response.response_package.content.response.faskes;
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nama,
                                id: item.kode
                            }
                        })
                    };
                }
            }
        }).addClass("form-control").on("select2:select", function(e) {
            //
        });

        $("body").on("click", ".daftar_sep", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            var antrian = $(this).attr("antrian");
            var allowSEP = $(this).attr("allow_sep");
            if(allowSEP === "1") {
                $("#btnProsesSEP").show();
            } else {
                $("#btnProsesSEP").hide();
            }
            $("#txt_bpjs_rm").val($("#rm_" + uid).html());

            $("#txt_bpjs_internal_poli").html($("#poli_" + uid).html());

            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/Asesmen/antrian-detail/" + antrian,
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.response_data[0];
                    var diagnosa_kerja = data.diagnosa_kerja;
                    var diagnosa_banding = data.diagnosa_banding;
                    var icd10_kerja = data.icd10_kerja;
                    var icd10_banding = data.icd10_banding;

                    $("#txt_bpjs_internal_dk").html(diagnosa_kerja);
                    $("#txt_bpjs_internal_db").html(diagnosa_banding);

                    for(var dKey in icd10_kerja)
                    {
                        $("#txt_bpjs_internal_icdk").append("<li>" + icd10_kerja[dKey].nama + "</li>");
                    }

                    for(var dKey in icd10_banding)
                    {
                        $("#txt_bpjs_internal_icdb").append("<li>" + icd10_banding[dKey].nama + "</li>");
                    }


                },
                error: function(response) {
                    console.log(response);
                }
            });

            $("#modal-sep-new").modal("show");
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
												"<td>"+ item.no_rm +"</td>" +
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


        function loadKelasRawat(){
            $.ajax({
                async: false,
                url:__HOSTAPI__ + "/BPJS/get_kelas_rawat_select2",
                type: "GET",
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                success: function(response){
                    var data = response.response_package.content.response.list;

                    $("#txt_bpjs_kelas_rawat option").remove();
                    for(var a = 0; a < data.length; a++) {
                        var selection = document.createElement("OPTION");

                        $(selection).attr("value", data[a].kode).html(data[a].nama);
                        $("#txt_bpjs_kelas_rawat").append(selection);
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }


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


<div id="modal-sep-new" class="modal fade" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-large-title">
                    <img src="<?php echo __HOSTNAME__;  ?>/template/assets/images/bpjs.png" class="img-responsive" width="275" height="45" style="margin-right: 50px" /> Surat Eligibilitas Peserta
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Pasien</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">No Kartu</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor" readonly>
                                    </div>
                                    <div class="col-12 col-md-3 mb-3 form-group">
                                        <label for="">NIK Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nik" readonly>
                                    </div>
                                    <div class="col-12 col-md-6 mb-6 form-group">
                                        <label for="">Nama Pasien</label>
                                        <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nama" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Informasi Rujukan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Nomor Medical Record (MR)</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_rm" readonly>
                                        </div>

                                        <div class="col-12 col-md-7 form-group">
                                            <label for="">Tanggal SEP</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tgl_sep" readonly value="<?php echo date('d F Y'); ?>">
                                        </div>
                                        <div class="col-12 col-md-9 form-group">
                                            <label for="">Faskes</label>
                                            <select class="form-control sep" id="txt_bpjs_faskes">
                                                <option value="<?php echo __KODE_PPK__; ?>">RSUD KAB. BINTAN - KAB. BINTAN (KEPRI)</option>
                                            </select>
                                        </div>


                                        <div class="col-12 col-md-8 form-group">
                                            <label for="">Jenis Pelayanan</label>
                                            <select class="form-control sep" id="txt_bpjs_jenis_layanan">
                                                <option value="2">Rawat Jalan</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-9 mb-9 form-group">
                                            <label for="">Kelas Rawat</label>
                                            <select class="form-control sep" id="txt_bpjs_kelas_rawat"></select>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="col-12 col-md-4 mb-4 form-group">
                                            <label for="">Jenis Asal Rujukan</label>
                                            <select class="form-control uppercase sep" id="txt_bpjs_jenis_asal_rujukan">
                                                <option value="1">Puskesmas</option>
                                                <option value="2">Rumah Sakit</option>
                                            </select>
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="">Asal Rujukan</label>
                                            <select data-width="100%" class="form-control uppercase sep" id="txt_bpjs_asal_rujukan"></select>
                                        </div>
                                        <div class="col-12 col-md-5 mb-4 form-group">
                                            <label for="">Tanggal Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_tanggal_rujukan">
                                        </div>
                                        <div class="col-12 col-md-6 mb-4 form-group">
                                            <label for="">Nomor Rujukan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_nomor_rujukan" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-12">
                            <div class="card">
                                <div class="card-header card-header-large bg-white d-flex align-items-center">
                                    <h5 class="card-header__title flex m-0 text-info"><i class="fa fa-hashtag"></i> Perobatan</h5>
                                </div>
                                <div class="card-body row">
                                    <div class="col-12 col-md-6 mb-6">
                                        <div class="col-12 col-md-8 mb-4 form-group">
                                            <label for="">Poli Tujuan</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_poli_tujuan" readonly>
                                        </div>
                                        <div class="col-12 col-md-12 form-group">
                                            <label for="">Diagnosa Awal</label>
                                            <input type="text" autocomplete="off" class="form-control uppercase" id="txt_bpjs_diagnosa_awal" readonly>
                                        </div>
                                        <div class="col-12 col-md-12 form-group">
                                            <label for="">Catatan</label>
                                            <textarea class="form-control" id="txt_bpjs_catatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 mb-6">
                                        <div class="alert alert-info">
                                            <div class="col-12 col-md-8 mb-4 form-group">
                                                <b for="">Poli Tujuan</b>
                                                <blockquote style="padding-left: 25px;">
                                                    <h6 id="txt_bpjs_internal_poli"></h6>
                                                </blockquote>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <h6 for="">Diagnosa Kerja</h6>
                                                <ol type="1" id="txt_bpjs_internal_icdk"></ol>
                                                <blockquote style="padding-left: 25px;">
                                                    <p id="txt_bpjs_internal_dk"></p>
                                                </blockquote>
                                            </div>
                                            <div class="col-12 col-md-12 form-group">
                                                <h6 for="">Diagnosa Banding</h6>
                                                <ol type="1" id="txt_bpjs_internal_icdb"></ol>
                                                <blockquote style="padding-left: 25px;">
                                                    <p id="txt_bpjs_internal_db"></p>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnProsesSEP">
                    <i class="fa fa-check"></i> Proses
                </button>

                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>