<script type="text/javascript">
	$(function(){

		var tableAntrianRadiologi = $("#table-antrian-radiologi").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Radiologi/antrian",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					$("#jlh-antrian").html(response.response_package.response_result);
					return response.response_package.response_data;
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
						return row["waktu_order"];
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
						return row["dokter"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/radiologi/antrian/" + row.uid + "\" class=\"btn btn-warning btn-sm\">" +
										"<i class=\"fa fa-sign-out-alt\"></i>" +
									"</a>" +
									"<a href=\"" + __HOSTNAME__ + "/radiologi/cetak/" + row.uid + "\" target='_blank' class=\"btn btn-primary btn-sm\">" +
										"<i class=\"fa fa-print\"></i>" +
									"</a>" +
									"<button id=\"rad_order_" + row.uid + "\" type='button' class=\"btn btn-success btn-sm btn-selesai-radiologi\" data-toggle='tooltip' title='Tandai selesai'>" +
										"<i class=\"fa fa-check\"></i>" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-selesai-radiologi", function() {
		    var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Orderan selesai akan langsung terkirim pada dokter yang melakukan permintaan pemeriksaan radiologi dan tidak dapat diubah lagi. Mohon pastikan data sudah benar",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Radiologi",
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "verifikasi_hasil",
                            uid: uid
                        },
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                Swal.fire(
                                    "Order Radiologi",
                                    "Pemeriksaan berhasil terkirim",
                                    "success"
                                ).then((result) => {
                                    tableAntrianRadiologi.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Order Radiologi",
                                    "Order gagal diproses",
                                    "error"
                                ).then((result) => {
                                    //
                                });
                            }
                        },
                        error:function(response) {
                            //
                        }
                    });
                }
            });
        });
	});
</script>