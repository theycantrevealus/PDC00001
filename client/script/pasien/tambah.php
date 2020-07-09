<script type="text/javascript">
	
	$(function(){
		var allData = {};

		loadTermSelectBox('panggilan', 14);
		loadTermSelectBox('suku', 15);
		loadTermSelectBox('pendidikan', 16);
		loadTermSelectBox('pekerjaan', 18);
		loadTermSelectBox('status_suami_istri', 19);
		loadTermSelectBox('alamat_kecamatan', 12);
		loadRadio('parent_jenkel','col-md-3', 'jenkel', 10);
		loadRadio('parent_goldar','col-md-2', 'goldar', 17);
		loadRadio2Step('parent_agama','col-md-4', 'col-md-2', 'agama', 11);

		$("#alamat_kecamatan").on('change', function(){
			var id_kec = $(this).val();

			loadTermItemsRecursiveSelectbox('alamat_kelurahan', id_kec);
		});

		$("#btnSubmit").click(function(){
			var no_rm = $("#rm_sub_1").val() + "-" + $("#rm_sub_2").val() + "-" + $("#rm_sub_3").val();
			allData.no_rm = no_rm;

			var agama = $("input[name='agama']:checked").val();
			var jenkel = $("input[name='jenkel']:checked").val();
			var goldar = $("input[name='goldar']:checked").val();

			$(".inputan").each(function(){
				var value = $(this).val();

				$this = $(this);
				if ($this.is('input') || $this.is('textarea')){
					value = value.toUpperCase();
				}

				var name = $(this).attr("name");

				allData[name] = value;
			});

			allData.agama = agama;
			allData.jenkel = jenkel;
			allData.goldar = goldar;

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

		$(".no_rm").on('keyup', function(){
			if (this.getAttribute && this.value.length == this.getAttribute("maxlength")) {
				var id = $(this).attr("id").split("_");
				id = id[id.length - 1];
				id = parseInt(id) + 1;

				var next = $("#rm_sub_" + id);
				next.focus();
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

	function loadRadio2Step(selector, parentcolclass, colclass, name, id){
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
	}

</script>