<script type="text/javascript">
	
	$(function(){
		var status_antrian = '<?= $_GET['antrian']; ?>';

		if(status_antrian === "true") {
		    $("#btnBatal").attr({
                "href": __HOSTNAME__ + "/rawat_jalan/resepsionis"
            });
        }

		var allData = {};
		loadTermSelectBox('panggilan', 3);
		loadTermSelectBox('suku', 6);
		loadTermSelectBox('pendidikan', 8);
		loadTermSelectBox('pekerjaan', 9);
		loadTermSelectBox('status_suami_istri', 10);
		//loadTermSelectBox('alamat_kecamatan', 12);
		loadTermSelectBox('goldar', 4);
		loadTermSelectBox('agama', 5);
		loadTermSelectBox('warganegara', 7);
		loadWilayah('alamat_provinsi', 'provinsi', '', 'Provinsi');
		loadRadio('parent_jenkel','col-md-6', 'jenkel', 2);
		/*loadRadio('parent_goldar','col-md-2', 'goldar', 17);
		loadRadio2Step('parent_agama','col-md-4', 'col-md-2', 'agama', 11);*/

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

		$("#nik").on('keyup', function(){
			let value = $(this).val();

			if (value.length == 16){
				if (cekNIK(value) == false){
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

		$("#no_rm").on('keyup', function() {
            let value = $(this).val();
            if ($(this).inputmask("unmaskedvalue").length == 6){
                if (cekNoRM(value)){
                    $("#no_rm").addClass("is-invalid");
                    $("#error-no-rm").html("No. RM sudah terdaftar");
                    $("#btnSubmit").attr("disabled", true);
                } else {
                    $("#no_rm").addClass("is-valid").removeClass("is-invalid");
                    $("#error-no-rm").html("");
                    $("#btnSubmit").removeAttr("disabled");
                }
            } else {
                $("#no_rm").addClass("is-invalid");
                $("#error-no-rm").html("No. RM harus 6 angka");
                $("#btnSubmit").attr("disabled", true);
            }
		});

		$("#form-add-pasien").submit(function() {
			/*var agama = $("input[name='agama']:checked").val();
			var jenkel = $("input[name='jenkel']:checked").val();
			var goldar = $("input[name='goldar']:checked").val();*/

			var no_rm = $("#no_rm").inputmask('unmaskedvalue');
			allData.no_rm = no_rm;

			var jenkel = $("input[name='jenkel']:checked").val();
			allData.jenkel = jenkel;
			var requiredItem = [];

			$(".inputan").each(function(){
				var value = $(this).val();
				//Cek required UI
				if($(this).hasClass("required")) {
					if (value == "" || value == null || value == undefined ) {
						//console.log(value);
						//$("#" + $(this).attr("id")).addClass("bg-danger").focus();
						$("label[for=\"" + $(this).attr("id") + "\"]").addClass("text-danger");
						requiredItem.push($(this).attr("id"));
					} else {
						$("label[for=\"" + $(this).attr("id") + "\"]").removeClass("text-danger");
						//$("#" + $(this).attr("id")).removeClass("bg-danger");
					}
				}

				

				if (value != "" && value != null){
					$this = $(this);
					if ($this.is('input') || $this.is('textarea')){
						value = value.toUpperCase();
					}

					if ($this.is('select')){
						value = parseInt(value);
					}

					var name = $(this).attr("name");

					allData[name] = value;
				}
			});

			if(requiredItem.length === 0) {
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Pasien",
					data: {
						request : "tambah-pasien",
						dataObj : allData
					},
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						if (status_antrian === "true"){ 		//redirect to tambah kunjungan
							if (
							    response.response_package.response_unique !== "" &&
                                response.response_package.response_unique !== undefined &&
                                response.response_package.response_unique !== null
                            ){	//check returning uid
								//Set Current Pasien dan Antrian Data
								localStorage.setItem("currentPasien", response.response_package.response_unique);
								//Notif loket lain yang sedang aktif pemanggilan yang sama?? Back Log??
								location.href = __HOSTNAME__ + '/rawat_jalan/resepsionis/tambah/' + response.response_package.response_unique;
							}
						} else {
						    if(response.response_package.response_result > 0) {
                                location.href = __HOSTNAME__ + "/pasien";
                            } else {
						        console.log(response);
                            }
						}
					},
					error: function(response) {
						console.log("Error : ");
						console.log(response);
					}
				});
			} else {
				$([document.documentElement, document.body]).animate({
					scrollTop: $("#" + requiredItem[0]).offset().top - 300
				}, 500);
			}

			return false;
		});

		$(".select2").select2({});

		$('#no_rm').inputmask('99-99-99');

		$('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });
	});

	function cekNoRM(no_rm) {
		var result = false;

		$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Pasien/cek-no-rm/" + no_rm,
			type: "GET",
            beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
            	if (response.response_package !== undefined){
            		if (response.response_package.response_data.length > 0){
            			result = true;
            		}
            	}
            },
            error: function(response) {
                console.log(response);
            }
		});

		return result;
	}

	function cekNIK(nik){
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
            			result = true;
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
									  	"<input type='radio' value='"+ MetaData[i].id +"' id='"+ name +"_"+ MetaData[i].id +"' name='"+ name +"' class='custom-control-input required'>" +
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
	                    
	                   // autoSelect(selector, MetaData[i].id , params);

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

</script>