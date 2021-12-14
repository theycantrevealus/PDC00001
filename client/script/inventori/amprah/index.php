<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function() {
		function getDateRange(target) {
			var rangeKwitansi = $(target).val().split(" to ");
			if(rangeKwitansi.length > 1) {
				return rangeKwitansi;
			} else {
				return [rangeKwitansi, rangeKwitansi];
			}
		}

        protocolLib = {
            amprah_new_approval_request: function(protocols, type, parameter, sender, receiver, time) {
                if(__MY_PRIVILEGES__.response_data[0].uid === __UIDKARUAPOTEKER__) {
                    notification ("info", "Amprah butuh approval!", 3000, "approval_amprah");
                    tableAmprah.ajax.reload();
                }
            }
        };

		var tableAmprah = $("#table-list-amprah").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d) {
					d.request = "get_amprah_request";
					d.from = getDateRange("#range_amprah")[0];
					d.to = getDateRange("#range_amprah")[1];
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var dataSet = response.response_package.response_data;
					if(dataSet == undefined) {
						dataSet = [];
					}

					response.draw = parseInt(response.response_package.response_draw);
					response.recordsTotal = response.response_package.recordsTotal;
					response.recordsFiltered = response.response_package.recordsFiltered;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Kode Amprah"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tanggal;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.kode_amprah;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.status_caption;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        console.log("Saya : " + __MY_PRIVILEGES__.response_data[0].uid);
                        console.log("Karu : " + __UIDKARUAPOTEKER__);
                        if(row.status === "A") {
                            
                            if(__MY_PRIVILEGES__.response_data[0].uid === __UIDKARUAPOTEKER__) {
                                return "<a href=\"" + __HOSTNAME__ + "/inventori/amprah/view/" + row.uid + "\" class=\"btn btn-sm btn-info\"><i class=\"fa fa-eye\"></i> Info</a>" +
                                "<button class=\"btn btn-sm btn-success btnApproveAmprah\" id=\"approve_" + row.uid + "\"><i class=\"fa fa-check\"></i> Approve</button>";
                            } else {
                                return "<a href=\"" + __HOSTNAME__ + "/inventori/amprah/view/" + row.uid + "\" class=\"btn btn-sm btn-info\"><i class=\"fa fa-eye\"></i> Info</a>";
                            }
                        } else {
                            return "<a href=\"" + __HOSTNAME__ + "/inventori/amprah/view/" + row.uid + "\" class=\"btn btn-sm btn-info\"><i class=\"fa fa-eye\"></i> Info</a>";
                        }
					}
				}
			]
		});

        $("body").on("click", ".btnApproveAmprah", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];
            Swal.fire({
                title: "Amprah Approval",
                text: "Approve permohonan?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Tidak",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        async:false,
                        data: {
                            request: "approve_permintaan_amprah",
                            uid: uid
                        },
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        success:function(response) {
                            if(response.response_package.response_result > 0) {
                                push_socket(
									__ME__,
									"amprah_new_approved",
									__UIDKARUAPOTEKER__,
									"Permohonan Amprah Baru",
									"info"
                                ).then(function() {
                                    Swal.fire(
                                        "Amprah Approval",
                                        "Amprah telah disetujui",
                                        "success"
                                    ).then((result) => {
                                        tableAmprah.ajax.reload();
                                    });
								});
                            }
                        },
                        error:function(response) {
                        }
                    });
                }
            });
        });

		$("#range_amprah").change(function() {
			tableAmprah.ajax.reload();
		});



        var tableAmprahFinish = $("#table-list-amprah-selesai").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[5, 10, 15, -1], [5, 10, 15, "All"]],
            serverMethod: "POST",
            "ajax":{
                url: __HOSTAPI__ + "/Inventori",
                type: "POST",
                data: function(d){
                    d.request = "get_amprah_request_finish";
                    d.non_gudang = true;
                    d.from = getDateRange("#range_amprah_selesai")[0];
                    d.to = getDateRange("#range_amprah_selesai")[1];
                },
                headers:{
                    Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
                },
                dataSrc:function(response) {
                    var dataSet = response.response_package.response_data;
                    if(dataSet == undefined) {
                        dataSet = [];
                    }

                    response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
                    return dataSet;
                }
            },
            autoWidth: false,
            language: {
                search: "",
                searchPlaceholder: "Cari Kode Amprah"
            },
            "columns" : [
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.autonum;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.tanggal;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.kode_amprah;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.pegawai.nama;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.status_caption;
                    }
                },
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return "<button class=\"btn btn-sm btn-info print_proses_amprah\" id=\"proses_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Detail</button>";
                    }
                }
            ]
        });


        $("#range_amprah_selesai").change(function() {
            tableAmprahFinish.ajax.reload();
        });


        $("body").on("click", ".print_proses_amprah", function() {
            var uid = $(this).attr("id").split("_");
            uid = uid[uid.length - 1];

            $.ajax({
                url:__HOSTAPI__ + "/Inventori/get_amprah_proses_detail/" + uid,
                async:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type:"GET",
                success:function(response) {

                    var data = response.response_package.response_data[0];
                    console.log(data);
                    targetAmprahNum = data.kode;
                    $("#nomor-amprah").html(data.kode);
                    $("#tanggal-proses").html(data.tanggal);
                    $("#pengamprah").html(data.pegawai.nama);
                    $("#pelaksana").html(data.amprah.pegawai_detail.nama + " <b class=\"text-info\">[" + data.amprah.pegawai_detail.unit_detail.nama + "]</b>");
                    $("#tanggal-amprah").html(data.amprah.tanggal);

                    $("#detail_amprah tbody").html("");
                    var autonum = 1;
                    for(var a in data.detail) {
                        if(parseFloat(data.detail[a].qty) > 0) {
                            $("#detail_amprah tbody").append("<tr>" +
                                "<td>" + autonum + "</td>" +
                                "<td>" + data.detail[a].item.nama + "</td>" +
                                "<td>" + data.detail[a].item.satuan_terkecil_info.nama + "</td>" +
                                "<td>" + data.detail[a].batch.batch + "</td>" +
                                "<td class=\"number_style\">" + number_format(parseFloat(data.detail[a].qty), 2, ".", ",") + "</td>" +
                                "<td>" + data.detail[a].batch.expired_date + "</td>" +
                                "</tr>" +
                                "<tr>" +
                                "<td></td>" +
                                "<td colspan=\"5\">" + data.detail[a].keterangan + "</td>" +
                                "</tr>");
                            autonum++;
                        }
                    }
                    $("#form-detail-amprah").modal("show");
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $("#btn_cetak_amprah").click(function () {
            var data = $("#data-print").html();
            $.ajax({
                async: false,
                url: __HOST__ + "miscellaneous/print_template/do_detail.php",
                beforeSend: function (request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "POST",
                data: {
                    __HOSTNAME__: __HOSTNAME__,
                    __PC_CUSTOMER__: __PC_CUSTOMER__,
                    __PC_CUSTOMER_ADDRESS__: __PC_CUSTOMER_ADDRESS__,
                    __PC_CUSTOMER_CONTACT__: __PC_CUSTOMER_CONTACT__,
                    __NAMA_SAYA__ : __MY_NAME__,
                    __JUDUL__ : "Surat Bukti Barang Keluar",
                    data: data

                },
                success: function (response) {
                    var containerItem = document.createElement("DIV");
                    $(containerItem).html(response);
                    $(containerItem).printThis({
                        importCSS: true,
                        base: false,
                        importStyle: true,
                        header: null,
                        footer: null,
                        pageTitle: targetAmprahNum + "",
                        afterPrint: function() {
                            $("#form-payment-detail").modal("hide");
                        }
                    });
                },
                error: function (response) {
                    //
                }
            });
            return false;
        });
	});
</script>
<div id="form-detail-amprah" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="data-print">
                <table class="form-mode table">
                    <tr>
                        <td>Nomor</td>
                        <td class="wrap_content">:</td>
                        <td>
                            <b id="nomor-amprah"></b>
                        </td>

                        <td>Tanggal Amprah</td>
                        <td class="wrap_content">:</td>
                        <td id="tanggal-amprah"></td>

                        <td>Diproses Staff Gudang</td>
                        <td class="wrap_content">:</td>
                        <td id="pengamprah"></td>
                    </tr>
                    <tr>
                        <td>Pengamprah</td>
                        <td>:</td>
                        <td id="pelaksana"></td>

                        <td>Tanggal Proses</td>
                        <td>:</td>
                        <td id="tanggal-proses"></td>

                        <td colspan="3"></td>
                    </tr>
                </table>
                <table class="table table-bordered largeDataType" id="detail_amprah">
                    <thead class="thead-dark">
                    <tr>
                        <th class="wrap_content">No</th>
                        <th style="width: 20%">Barang</th>
                        <th>Satuan</th>
                        <th class="wrap_content">Batch</th>
                        <th class="wrap_content">Qty</th>
                        <th>Kedaluarsa</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>