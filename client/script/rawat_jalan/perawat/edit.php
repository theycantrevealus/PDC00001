<script type="text/javascript">
	$(function(){
		var dataAssesmen,
			allData = {};
		
		var uid_assesmen = __PAGES__[3];

		dataAssesmen = loadAssesmen(uid_assesmen);
		if (dataAssesmen != ""){
			loadPasien(dataAssesmen.antrian);	
		}

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
				var value = $(this).val();			
				var name = $(this).attr("id");
				allData[name] = value;
			});

			$("input[type=radio]:checked").each(function(){
				var value = $(this).val();			
				var name = $(this).attr("name");
				allData[name] = value;
			});

			console.log(allData);

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/AssesmenRawatJalan",
				data: {
					request : "edit-assesmen",
					uid_assesmen : uid_assesmen,
					dataObj : allData
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
					console.log(response);
					location.href = __HOSTNAME__ + '/rawat_jalan/perawat';
				},
				error: function(response) {
					console.log("Error : ");
					console.log(response);
				}
			});
		});
	});

	function loadPasien(params){
		if (params != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/AssesmenRawatJalan/pasien-detail/" + params,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	                dataPasien = response.response_package.pasien;
	                dataAntrian = response.response_package.antrian;

	                $.each(dataPasien, function(key, item){
	                	$("#" + key).html(item)
	                });

	                 $.each(dataAntrian, function(key, item){
	                	$("#" + key).val(item);
	                });

	                if (dataPasien.id_jenkel == 2){
						$(".wanita").attr("hidden",true);
					} else {
						$(".pria").attr("hidden",true);
					}
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}
	}

	function loadAssesmen(params){
		var dataAssesmen;

		if (params != ""){
			$.ajax({
				async: false,
	            url:__HOSTAPI__ + "/AssesmenRawatJalan/assesmen-detail/" + params,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	                dataAssesmen = response.response_package.response_data[0];
	                var listName = [];

	                if (dataAssesmen != ""){
	                	$.each(dataAssesmen, function(key, item){
		                	$("#" + key).val(item);
		                	checkedRadio(key, item);
		                	checkedCheckbox(key, item);

		                	listName.push(key);
		                });
	                }
	            },
	            error: function(response) {
	                console.log(response);
	            }
	        });
		}

		return dataAssesmen;
	}

	function checkedRadio(name, value){
		var $radios = $('input:radio[name='+ name +']');

		if ($radios != ""){
			if($radios.is(':checked') === false) {
	       	 $radios.filter('[value="'+ value +'"]').prop('checked', true);
	    	}
		}
	}

	function checkedCheckbox(name, value){
		var $check = $('input:checkbox[name='+ name +']');
	    if ($check != ""){
		    if($check.is(':checked') === false) {
		        $check.filter('[value="'+ value +'"]').prop('checked', true);
		    }
		}		 
	}

</script>