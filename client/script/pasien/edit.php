<script type="text/javascript">
	
	$(function(){
		var allData = {};

		loadTermSelectBox('panggilan', 3);
		loadTermSelectBox('suku', 6);
		loadTermSelectBox('pendidikan', 8);
		loadTermSelectBox('pekerjaan', 9);
		//loadTermSelectBox('status_suami_istri', 10);
		loadTermSelectBox('alamat_kecamatan', 12);
		loadTermSelectBox('goldar', 4);
		loadTermSelectBox('agama', 5);
		loadTermSelectBox('warganegara', 7);
		loadTermSelectBox('status_pernikahan', 16);
		loadRadio('parent_jenkel','col-md-6', 'jenkel', 2);

		var uid_pasien = __PAGES__[2];
		var dataPasien = loadPasien(uid_pasien);

		$("#alamat_provinsi").on('change', function(){
			var id = $(this).val();

			loadWilayah('alamat_kabupaten', 'kabupaten', id, 'Kabupaten / Kota');
			resetSelectBox('alamat_kecamatan', "Kecamatan");
			resetSelectBox('alamat_kelurahan', "Kelurahan");
		});

		$("#alamat_kabupaten").on('change', function(){
			var id = $(this).val();

			loadWilayah('alamat_kecamatan', 'kecamatan', id, 'Kecamatan');
			resetSelectBox('alamat_kelurahan', "Kelurahan");
		});

		$("#alamat_kecamatan").on('change', function(){
			var id = $(this).val();

			loadWilayah('alamat_kelurahan', 'kelurahan', id, "Kelurahan");
		});

		$("#btnSubmit").click(function(){
			var no_rm = $("#no_rm").inputmask('unmaskedvalue');
			allData.no_rm = no_rm;

			var jenkel = $("input[name='jenkel']:checked").val();
			allData.jenkel = jenkel;

			$(".inputan").each(function(){
				var value = $(this).val();

				if (value != "" && value != null){
					$this = $(this);
					if ($this.is('input') || $this.is('textarea')){
						value = value.toUpperCase();
					}

					if ($this.is('select')){
						value = parseInt(value);
					}

					var name = $(this).attr("name");
					if (name == 'email'){
						value = value.toLowerCase();
					}

					allData[name] = value;
				}
			});

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Pasien",
				data: {
					request : "edit-pasien",
					dataObj : allData,
					uid: uid_pasien
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					location.href = __HOSTNAME__ + '/pasien';
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});

			return false;
		});

		/*$(".no_rm").on('keyup', function(){
			if (this.getAttribute && this.value.length == this.getAttribute("maxlength")) {
				var id = $(this).attr("id").split("_");
				id = id[id.length - 1];
				id = parseInt(id) + 1;

				var next = $("#rm_sub_" + id);
				next.focus();
			}
		});*/

		$("#no_rm").on('keyup', function(){
			let value = $(this).inputmask('unmaskedvalue');

			if (value.length == 6){
				if (cekNoRM(value, dataPasien.no_rm) == false){
					$("#no_rm").addClass("is-valid").removeClass("is-invalid");
					$("#error-no-rm").html("");
					$("#btnSubmit").removeAttr("disabled");
				} else {
					$("#no_rm").addClass("is-invalid");
					$("#error-no-rm").html("No. RM tidak tersedia");
					$("#btnSubmit").attr("disabled", true);
				}
			} else {
				$("#no_rm").addClass("is-invalid");
				$("#error-no-rm").html("No. RM harus 6 angka");
				$("#btnSubmit").attr("disabled", true);
			}
		});

		$("#nik").on('keyup', function(){
			let value = $(this).val();

			if (value.length == 16){
				if (cekNIK(value, dataPasien.nik) == false){
					$("#nik").addClass("is-valid").removeClass("is-invalid");
					$("#error-nik").html("");
					$("#btnSubmit").removeAttr("disabled");
				} else {
					$("#nik").addClass("is-invalid");
					$("#error-nik").html("NIK tidak tersedia");
					$("#btnSubmit").attr("disabled", true);
				}
			} else {
				$("#nik").addClass("is-invalid");
				$("#error-nik").html("NIK harus 16 angka");
				$("#btnSubmit").attr("disabled", true);
			}
		});

		$(".select2").select2({});
		
		$('#no_rm').inputmask('99-99-99');

		$('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });
	});

	function cekNoRM(no_rm, no_rm_lama) {
		var result = false;

		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Pasien/cek-no-rm/" + no_rm,
			type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
            	if (response.response_package != ""){
            		if (response.response_package.response_result > 0){
            			if (response.response_package.response_data[0].no_rm != no_rm_lama){
            				result = true;
            			}
            		}
            	}
            },
            error: function(response) {
                console.log(response);
            }
		});

		return result;
	}

	function cekNIK(nik, nik_lama){
		var result = false;

		$.ajax({
			async: false,
            url:__HOSTAPI__ + "/Pasien/cek-nik/" + nik,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
               if (response.response_package != ""){
            		if (response.response_package.response_result > 0){
            			if (response.response_package.response_data[0].nik != nik_lama){
            				result = true;
            			}
            		}
            	}
            },
            error: function(response) {
                console.log(response);
            }
        });

        return result;
	}

	function loadTermSelectBox(selector, id_term){
		$.ajax({
			async: false,
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

	function loadTermItemsRecursiveSelectbox(selector, id){
		$.ajax({
			async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items-recursive/" + id,
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

	function loadRadio(selector, colclass, name, id){
		$.ajax({
			async: false,
            url:__HOSTAPI__ + "/Terminologi/terminologi-items/" + id,
            type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = response.response_package.response_data;

                if (MetaData != ""){
                	var html = "";
                	for(i = 0; i < MetaData.length; i++){
	                    html += "<div class='"+ colclass +"'>" +
									"<div class='custom-control custom-radio'>" +
									  	"<input type='radio' value='"+ MetaData[i].id +"' id='"+ name +"_"+ MetaData[i].id +"' name='"+ name +"' class='custom-control-input' required>" +
									  	"<label class='custom-control-label' for='"+ name +"_"+ MetaData[i].id +"'>"+ MetaData[i].nama +"</label>" +
									"</div>" +
								"</div>";
	                }
         
	                $("#" + selector).html(html);
            	}
        	},
            error: function(response) {
                console.log(response);
            }
        });
	}


	function loadPasien(uid){
		var dataPasien = null;

		if (uid != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Pasien/pasien-detail/" + uid,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	                dataPasien = response.response_package.response_data[0];

	                $.each(dataPasien, function(key, item){
	                	$("#" + key).val(item);
	                });

	                loadSelected("alamat_provinsi", 'provinsi', '', dataPasien.alamat_provinsi);
	                loadSelected("alamat_kabupaten", 'kabupaten', dataPasien.alamat_provinsi, dataPasien.alamat_kabupaten);
	                loadSelected("alamat_kecamatan", 'kecamatan', dataPasien.alamat_kabupaten, dataPasien.alamat_kecamatan);
	                loadSelected("alamat_kelurahan", 'kelurahan', dataPasien.alamat_kecamatan, dataPasien.alamat_kelurahan);

	                checkedRadio('jenkel', dataPasien['jenkel']);
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}
		
		return dataPasien;
	}

	function loadWilayah(selector, parent, id, name){
		
		resetSelectBox(selector, name);

		$.ajax({
            url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
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

	function loadSelected(selector, parent, id, params){
		$.ajax({
            url:__HOSTAPI__ + "/Wilayah/"+ parent +"/" + id,
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
	                    if (MetaData[i].id == params) {
	                    	$(selection).attr("selected",true);
	                    	$("#" + selector).val(MetaData[i].id);
	                    	//$("#" + selector).trigger('change');
	                    };

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

	function autoSelect(selector, id, params){
		if (id == params){
        	$(selector).val(id);
        	$(selector).trigger('change');
        }
	}

	function checkedRadio(name, value){
		var $radios = $('input:radio[name='+ name +']');
	    if($radios.is(':checked') === false) {
	        $radios.filter('[value='+ value +']').prop('checked', true);
	    }
	}

</script>