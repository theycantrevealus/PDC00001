<script type="text/javascript">
	$(function(){
		var currentPasien = localStorage.getItem("currentPasien");
		var currentAntrianID = localStorage.getItem("currentAntrianID");

		/*alert(currentPasien);
		alert(currentAntrianID);*/

		var uid_pasien = __PAGES__[3];
		var dataPasien = loadPasien(uid_pasien);

		loadPenjamin();
		loadPoli();
		loadPrioritas();

		$("#departemen").on('change', function(){
			var poli = $(this).val();

			if (poli != ""){
				loadDokter(poli);
			}
		});
		
		$("#btnSubmit").click(function(){
			var dataObj = {};
			if(currentAntrianID != undefined || currentAntrianID != null) {
				$('.inputan').each(function(){
					var key = $(this).attr("id");
					var value = $(this).val();

					dataObj[key] = value;
				});

				dataObj.pasien = uid_pasien;
				dataObj.currentPasien = currentPasien;
				dataObj.currentAntrianID = currentAntrianID;

				console.log(dataObj);

				if(dataObj.departemen != null && dataObj.dokter != null && dataObj.penjamin != null && dataObj.prioritas != null) {
					$.ajax({
						async: false,
						url: __HOSTAPI__ + "/Antrian",
						data: {
							request : "tambah-kunjungan",
							dataObj : dataObj
						},
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type: "POST",
						success: function(response){
							//console.log(response.response_package)
							if(response.response_package.response_notif == 'K') {
								push_socket(__ME__, "kasir_daftar_baru", "*", "Biaya daftar pasien umum a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning");
							} else if(response.response_package.response_notif == 'P') {
								push_socket(__ME__, "kasir_daftar_baru", "*", "Antrian pasien a/n. " + response.response_package.response_data[0].pasien_detail.nama, "warning");
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
				} else {
					alert("Data belum lengkap");
				}
			}
			return false;
		});

		$(".select2").select2({});
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



	/*========== FUNC FOR LOAD PENJAMIN ==========*/
    function loadPenjamin(){
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
</script>