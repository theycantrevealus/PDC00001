<script type="text/javascript">
	$(function(){
		loadDokter();
		loadRuangan();

		$(".select2").select2({});
	});


	function loadDokter(){
    	$.ajax({
    		async: false,
            url:__HOSTAPI__ + "/Poli/poli-avail-dokter",
            type: "GET",
             beforeSend: function(request) {
                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
            },
            success: function(response){
                var MetaData = dataPoli = response.response_package.response_data;

                if (MetaData != ""){ 
                	for(i = 0; i < MetaData.length; i++){
	                    var selection = document.createElement("OPTION");

	                    $(selection).attr("value", MetaData[i].uid).html(MetaData[i].nama_dokter);
	                    $("#dokter_utama").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
    	})
    }

    function loadRuangan(){
    	$.ajax({
    		async: false,
            url:__HOSTAPI__ + "/Ruangan/ruangan",
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
	                    $("#ruangan").append(selection);
	                }
                }
            },
            error: function(response) {
                console.log(response);
            }
    	})
    }
</script>