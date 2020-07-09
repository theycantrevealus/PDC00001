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

		$("#btnSubmit").click(function(){
			/*var agama = $("input[name='agama']:checked").val();
			var jenkel = $("input[name='jenkel']:checked").val();
			var goldar = $("input[name='goldar']:checked").val();*/

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

			console.log(allData);
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
					location.href = __HOSTNAME__ + '/pasien';
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});

			return false;
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

	/*function loadRadio2Step(selector, parentcolclass, colclass, name, id){
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
                		var mark = i + 1;

                		if (i % 2 == 0){
                			html += "<div class="+ parentcolclass +">";
                		}

	                    html += "<div class='"+ colclass +"'>" +
									"<div class='custom-control custom-radio'>" +
									  	"<input type='radio' value='"+ MetaData[i].id +"' id='"+ name +"_"+ MetaData[i].id +"' name='"+ name +"' class='custom-control-input' required>" +
									  	"<label class='custom-control-label' for='"+ name +"_"+ MetaData[i].id +"'>"+ MetaData[i].nama +"</label>" +
									"</div>" +
								"</div>";

						if (mark % 2 == 0){
                			html += "</div>";
                		}
	                }

	                if (MetaData.length % 2 != 0){
	                	html += "</div>";
	                }
         
	                $("#" + selector).html(html);
            	}
        	},
            error: function(response) {
                console.log(response);
            }
        });
	}*/

</script>