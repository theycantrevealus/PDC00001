<script type="text/javascript">
	$(function(){

		var uid_pasien = __PAGES__[2];
		var dataPasien = loadPasien(uid_pasien);

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

	                console.log(dataPasien);

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
</script>