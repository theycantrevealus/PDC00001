<script type="text/javascript">
	$(function(){
		var allData = {};
		var uid_antrian = __PAGES__[3];

		loadPasien(uid_antrian);

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

			//console.log(allData);

			$.ajax({
				async: false,
				url: __HOSTAPI__ + "/AssesmenRawatJalan",
				data: {
					request : "tambah-assesmen",
					uid_antrian : uid_antrian,
					dataObj : allData
				},
				beforeSend: function(request) {
					request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
				},
				type: "POST",
				success: function(response){
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

	                console.log(dataPasien);

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
</script>