<script type="text/javascript">
	$(function(){
		var allData = {};
		var uid_antrian = __PAGES__[3];
		var dataPasien = loadPasien(uid_antrian);

		$(".select2").select2({});

		loadTermSelectBox('riwayat_transfusi_golongan_darah', 4);

		$("input[type=\"radio\"][name=\"rokok_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_merokok").removeAttr("disabled");
			} else {
				$("#riwayat_merokok").attr("disabled", "disabled");
			}
		});

		$("input[type=\"radio\"][name=\"miras_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_miras").removeAttr("disabled");
			} else {
				$("#riwayat_miras").attr("disabled", "disabled");
			}
		});

		$("input[type=\"radio\"][name=\"obt_terlarang_yes\"]").change(function() {
			if($(this).val() == "y") {
				$("#riwayat_obt_terlarang").removeAttr("disabled");
			} else {
				$("#riwayat_obt_terlarang").attr("disabled", "disabled");
			}
		});

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

		$('.numberonly').keypress(function(event){
            if (event.which < 48 || event.which > 57) {
                event.preventDefault();
            }
        });

        $("#program_kb").on('change', function(){
        	let status = $(this).val();

        	disableElementSelectBox("jenis-kb", status);
        });

        $("#ginekologi_status").on('change', function(){
        	let status = $(this).val();
        	disableElementSelectBox("ginekologi", status);
        });

        $("#eliminasi_bak").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("eliminasi_bak_lainnya", status, "Lainnya");
        });

        $("#komunikasi_bicara").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_bicara_lainnya", status, "Lainnya");
        });

        $("#komunikasi_hambatan").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_hambatan_lainnya", status, "Lainnya");
        });

        $("#komunikasi_kebutuhan_belajar").on('change', function(){
        	let status = $(this).val();
        	disableLainnya("komunikasi_kebutuhan_belajar_lainnya", status, "Lainnya");
        }); 

        $("input[name='cara_masuk']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('cara_masuk_lainnya', value, "Lainnya");
        });

        $("input[name='rujukan']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('ket_rujukan', value, 1);
        });
        
         $("input[name='kaji_resiko_ke_dokter']").on('change', function(){
        	let value = $(this).val();

        	disableLainnya('kaji_resiko_jam_dokter', value, 1);
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

	function disableCheckboxChild(parent, child){
		if (parent.checked == true){
        	$("." + child).removeAttr("disabled");
        } else {
        	$("." + child).val("").attr("disabled",true);
        }
	}

	function disableElementSelectBox(selector, value){
    	let $this = $("." + selector);

    	if (value == 0 || value == ""){
    		$this.attr("disabled",true);
    		
    		if ($this.is(':checkbox')) {
    			$this.prop('checked',false);
    		}
    	} else {
    		$this.removeAttr("disabled");
    	}
	}

	function disableLainnya(child_selector, value, comparison_value){
		let $this = $("." + child_selector);

		if (value == comparison_value){
    		$this.removeAttr("disabled");
    	} else {
    		$this.attr("disabled",true);
    	}
	}

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

		                if (MetaData.antrian.penjamin != <?= json_encode(__UIDPENJAMINBPJS__) ?>) {
		                	$(".rujukan-bpjs").attr("hidden", true);
		                } else {
		                }

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
		                	
		                	let program_kb = $("#program_kb").val();
		                	if (program_kb == 0 || program_kb == ""){
								disableElementSelectBox('jenis-kb', program_kb);	                		
		                	}

		                	let cara_masuk = $("input[name='cara_masuk']").val();
		                	disableLainnya('cara_masuk_lainnya', cara_masuk, "Lainnya");

		                	let rujukan = $("input[name='rujukan']").val();
		                	disableLainnya('ket_rujukan', rujukan, 1);

		                	if ($("#riwayat_keluarga_lainnya").is(':checked')) {
		                		$(".riwayat_keluarga_lainnya_ket").removeAttr("disabled");
		                	}

		                	if ($("#nyeri_lainnya").is(':checked')) {
		                		$(".nyeri_lainnya_ket").removeAttr("disabled");
		                	}
		                	
		                	let bicara = $("#komunikasi_bicara").val();
							disableLainnya('komunikasi_bicara_lainnya', bicara, "Lainnya");	                		
		                	
		                	let hambatan = $("#komunikasi_hambatan").val();		                	
							disableLainnya('komunikasi_hambatan_lainnya', hambatan, "Lainnya");

							let kebutuhan = $("#komunikasi_kebutuhan_belajar").val();		                	
							disableLainnya('komunikasi_kebutuhan_belajar_lainnya', kebutuhan, "Lainnya");

							let kaji_jam_ke_dokter = $("#kaji_resiko_ke_dokter").val();
		                	if (kaji_jam_ke_dokter == 0 || kaji_jam_ke_dokter == ""){
								disableElementSelectBox('kaji_resiko_jam_dokter', program_kb);	                		
		                	}

		                	if ($("#tatalaksana_siapkan_obat").is(':checked')) {
		                		$(".tatalaksana_siapkan_obat_ket").removeAttr("disabled");
		                	}

		                	if ($("#tatalaksana_beri_obat").is(':checked')) {
		                		$(".tatalaksana_beri_obat_ket").removeAttr("disabled");
		                	}

		                	if ($("#tatalaksana_konsul").is(':checked')) {
		                		$(".tatalaksana_konsul_ket").removeAttr("disabled");
		                	}
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