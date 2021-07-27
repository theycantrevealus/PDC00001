<script type="text/javascript">

	$(function(){

        protocolLib = {
            antrian_poli_baru: function(protocols, type, parameter, sender, receiver, time) {
                notification ("info", "Antrian poli baru", 3000, "notif_pasien_baru");
                tableAntrianPerawat.ajax.reload();
            },
            retur_barhasil: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianPerawat.ajax.reload();
            }
        };

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
					console.log(response);
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
						var selectorDataDokter = loadDokter(row["uid_poli"], row["uid_dokter"]);
						return "<select class=\"form-control selector_dokter\" antrian=\"" + row["uid"] + "\">" + selectorDataDokter + "</select>";
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

		$("body").on("change", ".selector_dokter", function() {
			Swal.fire({
                title: "Ubah Dokter?",
                showDenyButton: true,
                type: "warning",
                confirmButtonText: "Ya",
                confirmButtonColor: "#1297fb",
                denyButtonText: "Tidak",
                denyButtonColor: "#ff2a2a"
            }).then((result) => {
                if (result.isConfirmed) {
                    var dokter = $(this).val();
                    var uid = $(this).attr("antrian");

                    $.ajax({
                        async: false,
                        url:__HOSTAPI__ + "/Antrian",
                        type: "POST",
                        data:{
                            request:"ubah_dokter_antrian",
                            dokter: dokter,
                            uid:uid
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        success: function(response){
                            if(response !== undefined) {
                                if(response.response_package.response_result > 0) {
                                    push_socket(__ME__, "antrian_poli_ubah", dokter, "Antrian pasien baru telah diubah untuk Anda", "warning").then(function () {
                                        Swal.fire(
                                            'Berhasil diubah!',
                                            'Dokter berhasil diubah',
                                            'success'
                                        ).then((result) => {
                                            tableAntrianPerawat.ajax.reload();

                                        });
                                    });
                                }
                            }
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }
            });
		});


        /*Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            if(command !== undefined && command !== null && command !== "") {
                protocolLib[command](command, type, parameter, sender, receiver, time);
            }
        }*/

        setTimeout(function() {

            tableAntrianPerawat.ajax.reload();

        }, 5000);





	});


</script>