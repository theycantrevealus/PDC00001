<script type="text/javascript">
	$(function() {
	    var selectedKunjungan = "", selectedPenjamin = "";
		var tableAntrian= $("#table-antrian-rawat-jalan").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Asesmen/antrian-asesmen-medis",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
				    var filteredData = [];
				    var data = response.response_package.response_data;
				    for(var a = 0; a < data.length; a++) {
				        if(
				            data[a].uid_pasien === __PAGES__[3] &&
                            data[a].uid_kunjungan === __PAGES__[4] &&
                            data[a].uid_poli === __POLI_INAP__
                        ) {
				            filteredData.push(data[a]);
                        }
                    }
				    if(filteredData.length > 0) {
				        selectedKunjungan = filteredData[0].uid_kunjungan;
				        selectedPenjamin = filteredData[0].uid_penjamin;


				        $("#target_pasien").html(filteredData[0].pasien);
                        $("#nama_pasien").html("<span class=\"text-info\">[" + filteredData[0].no_rm + "]</span> " + filteredData[0].pasien);
                        $("#jenkel_pasien").html(filteredData[0].pasien_detail.jenkel_detail.nama);
                        $("#tanggal_lahir_pasien").html(filteredData[0].pasien_detail.tanggal_lahir_parsed);
                    } else {
				        //Pasien Detail
                        $.ajax({
                            url: __HOSTAPI__ + "/Pasien/pasien-detail/" + __PAGES__[3],
                            async:false,
                            beforeSend: function(request) {
                                request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                            },
                            type:"GET",
                            success:function(response) {
                                var pasienData = response.response_package.response_data;
                                $("#target_pasien").html(pasienData[0].nama);
                                $("#nama_pasien").html("<span class=\"text-info\">[" + pasienData[0].no_rm + "]</span> " + pasienData[0].nama);
                                $("#jenkel_pasien").html(pasienData[0].jenkel_detail.nama);
                                $("#tanggal_lahir_pasien").html(pasienData[0].tanggal_lahir_parsed);
                            },
                            error: function(response) {
                                console.log(response);
                            }
                        });
                    }

				    return filteredData;
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
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + row.uid + "/" + row.uid_pasien + "/" + row.uid_kunjungan + "\" class=\"btn btn-success btn-sm\">" +
										"<i class=\"fa fa-eye\"></i>" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("#btnTambahAsesmen").click(function() {
		    $(this).attr({
                "disabled": "disabled"
            }).removeClass("btn-info").addClass("btn-warning").html("<i class=\"fa fa-sync\"></i> Menambahkan Asesmen");

            var formData = {
                request: "tambah_asesmen",
                penjamin: __PAGES__[5],
                kunjungan: __PAGES__[4],
                pasien: __PAGES__[3],
                poli: __POLI_INAP__
            };

            $.ajax({
                url: __HOSTAPI__ + "/Inap",
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"POST",
                data: formData,
                success:function(response) {
                    location.href = __HOSTNAME__ + "/rawat_inap/dokter/antrian/" + response.response_package.response_values[0] + "/" + __PAGES__[3] + "/" + __PAGES__[4];
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });
	});
</script>