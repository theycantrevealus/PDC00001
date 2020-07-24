<script type="text/javascript">
	$(function(){
		var allData = {};
		var uid_antrian = __PAGES__[3];
		var dataPasien = loadPasien(uid_antrian);

		$(".select2").select2({});

		$("#btnSelesai").on('click', function(){

			$(".inputan").each(function(){
				var value = $(this).val();

				if (value != "" && value != null){
					$this = $(this);
					var name = $(this).attr("id");
					allData[name] = value;
				}
			});

			$("input[type=checkbox]:checked").each(function(){
				var name = $(this).attr("id");
				allData[name] = 1;
			});

			$("input[type=radio]:checked").each(function(){
				var value = $(this).val();
				if (value != ""){
					var name = $(this).attr("name");
					allData[name] = value;
				}
			});

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/Asesmen",
				data: {
					request : "update_asesmen_rawat",
					dataAntrian : dataPasien.antrian,
					dataPasien: dataPasien.pasien,
					dataObj : allData
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					//console.log(response);
					location.href = __HOSTNAME__ + '/rawat_jalan/perawat';
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});
			// /console.log(dataPasien.antrian);
		});
	});

	function loadPasien(params){
		var MetaData = null;

		if (params != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/Asesmen/asesmen-rawat-detail/" + params,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	            	console.log(response);
	            	if (response.response_package != ""){
	            		MetaData = response.response_package;

		                $.each(MetaData.pasien, function(key, item){
		                	$("#" + key).html(item)
		                });

		                $.each(MetaData.antrian, function(key, item){
		                	$("#" + key).val(item);
		                });

						if (MetaData.pasien.id_jenkel == 2){
							$(".wanita").attr("hidden",true);
						} else {
							$(".pria").attr("hidden",true);
						}

						if (MetaData.asesmen_rawat != ""){
		                	$.each(MetaData.asesmen_rawat, function(key, item){
			                	$("#" + key).val(item);
			                	checkedRadio(key, item);
			                	checkedCheckbox(key, item);
			                });
		                }
	            	}

	            	console.log(MetaData);
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}

		return MetaData;
	}

	function checkedRadio(name, value){
		var $radios = $('input:radio[name='+ name +']');

		if ($radios != ""){
			if($radios.is(':checked') === false) {
				if (value != null && value != ""){
	       	 		$radios.filter('[value="'+ value +'"]').prop('checked', true);
	    		}
	    	}
		}
	}

	function checkedCheckbox(name, value){
		var $check = $('input:checkbox[name='+ name +']');

	    if ($check != ""){
		    if($check.is(':checked') === false) {
		    	if (value != null && value != ""){
		    		$check.filter('[value="'+ value +'"]').prop('checked', true);
		    	}
		    }
		}		 
	}
</script>