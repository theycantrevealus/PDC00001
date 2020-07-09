<script type="text/javascript">
	
	$(function(){
		var allData = {};

		loadTermSelectBox('panggilan', 3);
		loadTermSelectBox('suku', 6);
		loadTermSelectBox('pendidikan', 8);
		loadTermSelectBox('pekerjaan', 9);
		loadTermSelectBox('status_suami_istri', 10);
		loadTermSelectBox('alamat_kecamatan', 12);
		loadTermSelectBox('goldar', 4);
		loadTermSelectBox('agama', 5);
		loadTermSelectBox('warganegara', 7);
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

		$(".no_rm").on('keyup', function(){
			if (this.getAttribute && this.value.length == this.getAttribute("maxlength")) {
				var id = $(this).attr("id").split("_");
				id = id[id.length - 1];
				id = parseInt(id) + 1;

				var next = $("#rm_sub_" + id);
				next.focus();
			}
		});

		$(".select2").select2({});
		
		$('#no_rm').inputmask('999-999-999');

		$('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });
	});

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