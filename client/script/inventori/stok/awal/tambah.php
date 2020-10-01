<script type="text/javascript">
	$(function(){

		loadGudang();

	});

	function loadGudang(){
		resetSelectBox('gudang', "Gudang");

		$.ajax({
            url:__HOSTAPI__ + "/Inventori/gudang",
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
	                    $("#gudang").append(selection);
	                }
                }
                
            },
            error: function(response) {
                console.log(response);
            }
        });
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

	function resetSelectBox(selector, name){
		$("#"+ selector +" option").remove();
		var opti_null = "<option value='' selected disabled>Pilih "+ name +" </option>";
        $("#" + selector).append(opti_null);
	}

</script>