<script type="text/javascript">
	$(function(){

		var tableAntrianLabor = $("#table-antrian-labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-antrian-backend";
                    d.mode = "reqular";
                    d.status = 'P';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Order"
            },
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
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/antrian/" + row['uid'] + "\" class=\"btn btn-warning btn-sm\">" +
                                    "<i class=\"fa fa-sign-out-alt\"></i>" +
                                "</a>" +
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/cetak/" + row['uid'] + "\" target='_blank' class=\"btn btn-primary btn-sm\">" +
                                    "<i class=\"fa fa-print\"></i>" +
                                "</a>" +
                                "<button type=\"button\" id=\"order_lab_" + row.uid + "\" class=\"btn btn-success btn-sm btn-selesai\" data-toggle='tooltip' title='Tandai selesai'>" +
                                    "<i class=\"fa fa-check\"></i>" +
                                "</a>" +
                            "</div>";
					}
				}
			]
		});

        $("body").on("click", ".btn-selesai", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Orderan selesai akan langsung terkirim pada dokter yang melakukan permintaan pemeriksaan laboratorium dan tidak dapat diubah lagi. Mohon pastikan data sudah benar",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Belum",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: __HOSTAPI__ + "/Laboratorium",
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
                                    "Order Laboratorium",
                                    "Pemeriksaan berhasil terkirim",
                                    "success"
                                ).then((result) => {
                                    tableAntrianLabor.ajax.reload();
                                });
                            } else {
                                Swal.fire(
                                    "Order Laboratorium",
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



        $("#range_history").change(function() {
            tableHistoryLabor.ajax.reload();
        });

        function getDateRange(target) {
            var rangeHistory = $(target).val().split(" to ");
            if(rangeHistory.length > 1) {
                return rangeHistory;
            } else {
                return [rangeHistory, rangeHistory];
            }
        }

        var tableHistoryLabor = $("#table-history-labor").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Laboratorium",
                type: "POST",
                data: function(d) {
                    d.request = "get-antrian-backend";
                    d.from = getDateRange("#range_history")[0];
                    d.to = getDateRange("#range_history")[1];
                    d.mode = "history";
                    d.status = 'D';
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var returnedData = [];
                    if(response == undefined || response.response_package == undefined) {
                        returnedData = [];
                    } else {
                        returnedData = response.response_package.response_data;
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;

                    return returnedData;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Nomor Order"
            },
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
                            "<a href=\"" + __HOSTNAME__ + "/laboratorium/view/" + row['uid'] + "/\" class=\"btn btn-info btn-sm\">" +
                                "<i class=\"fa fa-eye\"></i> Detail" +
                            "</a>" +
                            "</div>";
                    }
                }
            ]
        });




        //SOCKET
        Sync.onmessage = function(evt) {
            var signalData = JSON.parse(evt.data);
            var command = signalData.protocols;
            var type = signalData.type;
            var sender = signalData.sender;
            var receiver = signalData.receiver;
            var time = signalData.time;
            var parameter = signalData.parameter;

            if(command !== undefined && command !== null && command !== "") {
                protocolLib.command(command, type, parameter, sender, receiver, time);
            } else {
                console.log(command);
            }
        }



        let protocolLib = {
            antrian_laboratorium_baru: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                notification (type, parameter, 3000, "notif_lab_baru");
            },
            antrian_laboratorium_selesai: function(protocols, type, parameter, sender, receiver, time) {
                tableAntrianLabor.ajax.reload();
                tableHistoryLabor.ajax.reload();
            }
        };
	});
</script>