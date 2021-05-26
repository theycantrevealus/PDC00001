<script type="text/javascript">
	$(function(){
		//var currentPasien = localStorage.getItem("currentPasien");
		//var currentAntrianID = localStorage.getItem("currentAntrianID");

		/*alert(currentPasien);
		alert(currentAntrianID);*/

		loadPenjamin();
		loadPoli();
		loadPrioritas();

		let uid_pasien = __PAGES__[4];
        let id_reservasi = __PAGES__[3];
		let dataPasien = loadPasien(uid_pasien);
		loadReservasiOnlineData(id_reservasi);

		$("#departemen").on('change', function(){
			var poli = $(this).val();

			if (poli != ""){
				loadDokter(poli);
			}
		});
		
		$("#btnSubmit").click(function(){
			var dataObj = {};
			// if(currentAntrianID != undefined || currentAntrianID != null) {
				$('.inputan').each(function(){
					var key = $(this).attr("id");
					var value = $(this).val();

					dataObj[key] = value;
				});

				dataObj.pasien = uid_pasien;
				//dataObj.currentPasien = currentPasien;
				//dataObj.currentAntrianID = currentAntrianID;

				console.log(dataObj);

				if(dataObj.departemen != null && dataObj.dokter != null && dataObj.penjamin != null && dataObj.prioritas != null) {
					if(dataObj.penjamin == __UIDPENJAMINBPJS__) {
						$("#modal-sep").modal("show");
						$("#btnProsesPasien").hide();
						$("#hasil_bpjs").hide();
					} else {
						$.ajax({
							async: false,
							url: __HOSTAPI__ + "/AntrianOnline",
							data: {
								request : "tambah_kunjungan",
								dataObj : dataObj
							},
							beforeSend: function(request) {
								request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
							},
							type: "POST",
							success: function(response){
								console.log("Berhasil");
                                console.log(response);
								// localStorage.getItem("currentPasien");
                                // localStorage.getItem("currentAntrianID");

							    if(response.response_package.response_notif == 'K') {

									updateStatusReservasiOnline(id_reservasi);

									push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then( function() {
                                        Swal.fire(
                                            'Berhasil ditambahkan!',
                                            'Silahkan arahkan pasien ke kasir',
                                            'success'
                                        ).then((result) => {
                                            location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                        });
                                    });

					
								} else if(response.response_package.response_notif == 'P') {

									updateStatusReservasiOnline(id_reservasi);

									push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                        Swal.fire(
                                            'Berhasil ditambahkan!',
                                            'Silahkan arahkan pasien ke poli',
                                            'success'
                                        ).then((result) => {
                                            location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
                                        });
                                    });

								} else {
									console.log("command not found");
								}

							},
							error: function(response) {
								console.log("Error : ");
								console.log(response);
							}
						});
					}
				} else {
					alert("Data belum lengkap");
				}
			//}
			return false;
		});

		$("#btnProsesPasien").click(function() {
			var dataObj = {};
			$('.inputan').each(function(){
				var key = $(this).attr("id");
				var value = $(this).val();

				dataObj[key] = value;
			});

			dataObj.pasien = uid_pasien;
			dataObj.currentPasien = currentPasien;
			dataObj.currentAntrianID = currentAntrianID;
			if(dataObj.departemen != null && dataObj.dokter != null && dataObj.penjamin != null && dataObj.prioritas != null) {
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/AntrianOnline",
					data: {
						request : "tambah_kunjungan",
						dataObj : dataObj
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						//console.log(response)
						if(response.response_package.response_notif == 'K') {


							
							push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                updateStatusReservasiOnline(id_reservasi);
                            });
						} else if(response.response_package.response_notif == 'P') {



							push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning").then(function () {
                                updateStatusReservasiOnline(id_reservasi);
                            });
						} else {
							console.log("command not found");
						}

						localStorage.getItem("currentPasien");
						localStorage.getItem("currentAntrianID");
						location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis';
					},
					error: function(response) {
						console.log("Error : ");
						console.log(response);
					}
				});
			}
		});

		$(".select2").select2({});

		$("#hasil_bpjs").hide();
		$("#btnProsesPasien").hide();

		$("#btnCariPasien").click(function() {
			$("#hasil_bpjs").hide();
			$("#btnProsesPasien").hide();
			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/BPJS",
				data: {
					request : "cek_peserta",
					no_bpjs: $("#txt_no_bpjs").val()
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					var data = response.response_package;
					if(data.metaData.code == 200) {

						$("#hasil_bpjs").fadeIn();
						var pasienData = data.response.peserta;
						if(pasienData.statusPeserta.keterangan == "AKTIF") {
							if(pasienData.nik != dataPasien.nik) { //Cek NIK
								$("#status_bpjs").addClass("text-danger").removeClass("text-success");
								$("#status_bpjs").html("NIK tidak sama");
							} else {
								$("#status_bpjs").addClass("text-success").removeClass("text-danger");
								$("#btnProsesPasien").show();
								$("#status_bpjs").html(pasienData.statusPeserta.keterangan);
							}
						} else {
							$("#status_bpjs").addClass("text-danger").removeClass("text-success");
						}
						$("#pekerjaan_pasien").html(pasienData.jenisPeserta.keterangan);
						$("#nama_pasien").html(pasienData.nama);
						$("#nik_pasien").html(pasienData.nik);
						$("#nomor_peserta").html(pasienData.noKartu);
						$("#tll_pasien").html(pasienData.tglLahir);
						//$("#faskes_pasien").html(pasienData.provUmum.kdProvider + " " + pasienData.provUmum.nmProvider);
						$("#faskes_pasien").html(pasienData.provUmum.nmProvider);
						$("#usia_pasien").html(pasienData.umur.umurSaatPelayanan);
						$("#kelamin_pasien").html((pasienData.sex == "L") ? "Laki-laki" : "Perempuan");
						
						$("#tanggal_kartu").html(pasienData.tglCetakKartu);

						//TAT Tanggal Akhir Kartu
						//TMT Tanggal Mulai Kartu

					} else if(data.metaData.code == 201) {
						//Tidak tidak ditemukan
					}
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});
		});
	});

	function loadPasien(uid){

		var dataPasien = null;

		if (uid != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Antrian/pasien-detail/" + uid,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	            	dataPasien = response.response_package;
	            	$.each(dataPasien, function(key, item){
	                	$("#" + key).val(item);
	                });
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}
		
		return dataPasien;
	}


	function loadReservasiOnlineData(id_reservasi){	//id reservasi online

		let form_data = {
			'request' : 'get-reservation-data-based-id',
			'id' : id_reservasi
		}

		$.ajax({
			async: false,
			url: "https://appsehat.rsudbintan.com/api/simrs.api.php",
			type: "POST",
			data : form_data,
			success: function(response){
				
				if (response != "" && response != undefined){

					let metaData = JSON.parse(response);
					
					if (metaData.length > 0) {
						
						$("#penjamin").val(metaData[0].uid_penjamin).trigger('change');
						$("#departemen").val(metaData[0].uid_poli).trigger('change');
						loadDokter(metaData[0].uid_poli);

					}

				}
			},
			error: function(response) {
				console.log(response);
			}
		});

	}


	/*========== FUNC FOR LOAD PENJAMIN ==========*/
    function loadPenjamin() {
        var dataPenjamin = null;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Penjamin/penjamin",
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
	                    $("#penjamin").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataPenjamin;
    }

    function loadPoli(){
    	var dataPoli = null;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Poli/poli-available",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPoli = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama);
	                    $("#departemen").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });

        return dataPoli;
    }

    function loadPrioritas(){
    	var term = 11;

        $.ajax({
            async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + term,
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
	                    $("#prioritas").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function loadDokter(poli){
    	resetSelectBox('dokter', 'Dokter');

    	$.ajax({
    		async: false,
            url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + poli,
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPoli = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].dokter).html(MetaData[i].nama);
	                    $("#dokter").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
    	})
    }

    function resetSelectBox(selector, name){
		$("#"+ selector +" option").remove();
		var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
	}

	function updateStatusReservasiOnline(id_reservasi) {
		$.ajax({
			url: "https://appsehat.rsudbintan.com/api/simrs.api.php",
			type:"POST",
			data:{
				request: "acc-reservation",
				id: id_reservasi
			},
			success:function(response){
				//RefreshDataTable2("#tabelAntrian", "https://appsehat.rsudbintan.com/api/simrs.api.php", {request: "get-reservation-data"});
			}
		});
	}

</script>
<div id="modal-sep" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-large-title">Pengecekan SEP</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group col-md-12">
					<div class="col-md-12">
						<div class="row">
							<label for="txt_cari">Cek Peserta BPJS</label>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="search-form search-form--light input-group-lg col-md-10">
								<input type="text" class="form-control" placeholder="No. BPJS" id="txt_no_bpjs" />
							</div>
							<div class="col-md-2">
								<button class="btn btn-success" id="btnCariPasien">
									<i class="fa fa-search"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group col-md-12" >
					<table class="table table-striped" id="hasil_bpjs">
						<tr><td width="120px">No. Peserta</td><td width="10px">:</td><td id="nomor_peserta"><?php echo $bpjs_nomor;?></td></tr>
						<tr><td>NIK</td><td>:</td><td id="nik_pasien"></td></tr>
						<tr><td>Nama</td><td>:</td><td id="nama_pasien"></td></tr>
						<tr><td>Tanggal Lahir</td><td>:</td><td id="tll_pasien"></td></tr>
						<tr><td>Usia</td><td>:</td><td id="usia_pasien"></td></tr>
						<tr><td>Jenis Kelamin</td><td>:</td><td id="kelamin_pasien"></td></tr>
						<tr><td>Pekerjaan</td><td>:</td><td id="pekerjaan_pasien"></td></tr>
						<tr><td>Faskes Pertama</td><td>:</td><td id="faskes_pasien"></td></tr>
						<tr><td>Tanggal Kartu</td><td>:</td><td id="tanggal_kartu"></td></tr>
						<tr><td>Status</td><td>:</td><td id="status_bpjs"></td></tr>
					</table>
				</div>
				
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" id="btnProsesPasien">
					<i class="fa fa-check"></i> Proses
				</button>
				
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div> 
	</div> 
</div>