<script type="text/javascript">

	$(function(){

        var antrian_count = 0;
		function loadDokter(poli, selected = ""){
			var populateDokter = "";
	    	$.ajax({
	    		async: false,
	            url:__HOSTAPI__ + "/Poli/poli-set-dokter/" + poli,
	            type: "GET",
	            beforeSend: function(request) {
	                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
	            },
	            success: function(response){
	                var MetaData = dataPoli = response.response_package.response_data;

	                if (MetaData != ""){ 
	                	for(var i = 0; i < MetaData.length; i++){

	                		populateDokter += "<option " + ((MetaData[i].dokter == selected) ? "selected=\"selected\"" : "") + " value=\"" + MetaData[i].dokter + "\">" + MetaData[i].nama + "</option>";

		                }
	                }
	            },
	            error: function(response) {
	                console.log(response);
	            }
	    	});
	    	return populateDokter;
	    }

        var tableAntrianPerawat = $("#table-antrian-perawat").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-rawat",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					antrian_count = response.response_package.length;
					return response.response_package;
				}
			},
			autoWidth: false,
			"bInfo" : false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["waktu_masuk"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["no_rm"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["pasien"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["departemen"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						// var selectorDataDokter = loadDokter(row["uid_poli"], row["uid_dokter"]);
						// return "<select class=\"form-control selector_dokter\" antrian=\"" + row["uid"] + "\">" + selectorDataDokter + "</select>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row["penjamin"];
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.prioritas.nama;
                    }
                },
				/*{
					"data" : null, render: function(data, type, row, meta) {
						return row["user_resepsionis"];
					}
				},*/
				{
					"data" : null, render: function(data, type, row, meta) {
						var button = "<a href='"+ __HOSTNAME__ +"/rawat_jalan/perawat/antrian/"+ row['uid'] +"' class='btn btn-info' data-toggle='tooltip' title='Isi Assesmen Pasien'><i class='fa fa-address-card'></i> Proses</a>";

						if (row['status_asesmen'] === true){
							button = "<a href='"+ __HOSTNAME__ +"/rawat_jalan/perawat/antrian/"+ row['uid'] +"' class='btn btn-success' data-toggle='tooltip' title='Edit Assesmen Pasien'><i class='fa fa-check-circle'></i> Edit</a>";
						}

						return "<div class=\"btn-group \" role=\"group\" aria-label=\"Basic example\">" + button + "</div>";
					}
				}
			],
			rowCallback: function (row, data) {
			    if (data['status_assesmen'] === true){
					$(row).find("td").css({
                        "background":"#fffcd0",
                        "color":"#000"
					});
				} else {
                    $(row).find("td").css({
                        "background":"#E0F5E5",
                        "color":"#000"
                    });
                }

                $(row).find("select").select2();
				//$(".selector_dokter").select2();
			}
		});

        $("#jlh-antrian").html(antrian_count);


    });
</script>
