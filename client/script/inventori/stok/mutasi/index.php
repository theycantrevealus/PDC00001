<script src="<?php echo __HOSTNAME__; ?>/plugins/DataTables/Responsive-2.2.5/js/dataTables.responsive.min.js"></script>
<link type="text/css" href="<?php echo __HOSTNAME__; ?>/plugins/DataTables/Responsive-2.2.5/css/responsive.dataTables.min.css" rel="stylesheet" />
<script type="text/javascript">
	$(function() {
	    var targettedUID = ""
		function getDateRange(target) {
			var rangeKwitansi = $(target).val().split(" to ");
			if(rangeKwitansi.length > 1) {
				return rangeKwitansi;
			} else {
				return [rangeKwitansi, rangeKwitansi];
			}
		}


        function reCheckStatus(currentStatus) {
            if(currentStatus === "A") {
                $("#btnTerimaMutasi").hide();
            } else {
                $("#btnTerimaMutasi").show();
            }
        }

		var tableAmprah = $("#table-list-amprah").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[20, 50, -1], [20, 50, "All"]],
			serverMethod: "POST",
            responsive: {
                details: {
                    type: 'column'
                }
            },
            columnDefs: [{
                className: 'control',
                orderable: false,
                targets: 0
            }, {
                ordering: false,
                orderable: false,
                targets: 1
            }],
            autoWidth: false,
			"ajax":{
				url: __HOSTAPI__ + "/Inventori",
				type: "POST",
				data: function(d){
					d.request = "get_mutasi_request";
					d.from = getDateRange("#range_amprah")[0];
					d.to = getDateRange("#range_amprah")[1];
					d.inap = true;
				},
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
                    console.clear();
                    console.log(response);
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
				searchPlaceholder: "Cari Kode Mutasi"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5><input type=\"hidden\" id=\"keterangan_" + row.uid + "\" value=\"" + row.keterangan + "\" />";
					}
				},
                {
					"data" : null, render: function(data, type, row, meta) {
						return "<button class=\"btn btn-sm btn-info detail_mutasi\" id=\"mutasi_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Detail</button>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"tanggal_" + row.uid + "\">" + row.tanggal + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<span class=\"wrap_content\" status=\"" + row.status + "\" id=\"kode_" + row.uid + "\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/bill.png\"> " + row.kode + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/wholesaler.png\"> <b id=\"unit_asal_" + row.uid + "\">" + row.dari.nama + "</b></span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/wholesaler.png\"> <b uid=\"" + row.ke.uid + "\" id=\"unit_tujuan_" + row.uid + "\">" + row.ke.nama + "</b></span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span class=\"wrap_content\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/employee.png\"> <b uid=\"" + row.pegawai.uid + "\" id=\"oleh_" + row.uid + "\">" + row.pegawai.nama + "</b></span>";
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return (row.status === "R") ? "<span class=\"text-success\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/logistic/check-list.png\"> Diterima</span>" : ((row.status === "N") ? "<span class=\"text-info\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/logistic/return.png\"> Baru</span>" : "<span class=\"text-warning\"><img class=\"icon-pack\" src=\"" + __HOSTNAME__ + "/template/assets/images/icons/logistic/trolley.png\"> Dibatalkan</span>");
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
                        var childRowTemplate = "<div class=\"row-child-template\">" + ((row.mut_resep_pasien !== undefined && row.mut_resep_pasien !== null) ? row.mut_resep_pasien : "Mutasi Biasa") + "</div>";
						return childRowTemplate;
					}
				}
			]
		});

		$("#range_amprah").change(function() {
			tableAmprah.ajax.reload();
		});

		$("#btnBatalkanMutasi").click(function() {
            Swal.fire({
                title: "Batalkan Mutasi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "proses_mutasi",
                            status: "C",
                            uid: targettedUID
                        },
                        success: function(resp) {
                            if(resp.response_package.response_result > 0) {
                                tableAmprah.ajax.reload();
                                $("#detail-mutasi").modal("hide");
                            }
                        },
                        error: function(resp) {
                            console.clear();
                            console.log(resp);
                        }
                    });
                }
            });
        });

		//Todo: Disable tombol ini saat sedang opname
        $("#btnTerimaMutasi").click(function() {
            Swal.fire({
                title: "Terima Mutasi?",
                showDenyButton: true,
                confirmButtonText: "Ya",
                denyButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url:__HOSTAPI__ + "/Inventori",
                        beforeSend: function(request) {
                            request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                        },
                        type:"POST",
                        data: {
                            request: "proses_mutasi",
                            status: "R",
                            igd: "Y",
                            inap: "Y",
                            uid: targettedUID
                        },
                        success: function(resp) {
                            console.clear();
                            console.log({
                                request: "proses_mutasi",
                                status: "R",
                                uid: targettedUID
                            });
                            console.log(resp);
                            if(resp.response_package.response_result > 0) {
                                tableAmprah.ajax.reload();
                                $("#detail-mutasi").modal("hide");
                            }
                        },
                        error: function(resp) {
                            console.clear();
                            console.log(resp);
                        }
                    });
                }
            });
        });

		$("body").on("click", ".detail_mutasi", function() {
		    var uid = $(this).attr("id").split("_");
		    uid = uid[uid.length - 1];

            targettedUID = uid;

            currentStatus = checkStatusGudang(__GUDANG_APOTEK__, "#warning_allow_transact_opname");
            reCheckStatus(currentStatus);

            $("#kode_mutasi").html($("#kode_" + uid).html());
		    $("#unit_asal").html($("#unit_asal_" + uid).html());
            $("#unit_tujuan").html($("#unit_tujuan_" + uid).html());
            $("#pegawai_proses").html($("#oleh_" + uid).html());
            $("#tanggal_mutasi").html($("#tanggal_" + uid).html());
            $("#keterangan_tambahan").html($("#keterangan_" + uid).val());

            if($("#kode_" + uid).attr("status") === "N") {
                if($("#oleh_" + uid).attr("uid") === __ME__) {
                    $("#btnBatalkanMutasi").show();
                } else {
                    $("#btnBatalkanMutasi").hide();
                }

                if($("#unit_tujuan_" + uid).attr("uid") === __UNIT__.gudang) {
                    $("#btnTerimaMutasi").show();
                } else {
                    $("#btnTerimaMutasi").hide();
                }
            } else {
                $("#btnBatalkanMutasi").hide();
                $("#btnTerimaMutasi").hide();
            }

            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/Inventori/get_mutasi_item/" + uid,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response){
                    var data = response.response_package.response_data;
                    $("#mutasi_detail_item tbody tr").remove();
                    for(var k in data) {
                        $("#mutasi_detail_item tbody").append(
                            "<tr>" +
                                "<td>" + (parseInt(k) + 1) + "</td>" +
                                "<td>" + data[k].item.nama + "</td>" +
                                "<td>" + data[k].batch.batch + "</td>" +
                                "<td>" + data[k].item.nama + "</td>" +
                                "<td class=\"number_style\">" + data[k].qty + "</td>" +
                                "<td>" + data[k].keterangan + "</td>" +
                            "</tr>"
                        );
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
		    $("#detail-mutasi").modal("show");
		    return false;
        });
	});
</script>


<div id="detail-mutasi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kode_mutasi"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-header-large bg-white">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table form-mode">
                                            <tr>
                                                <td>Dari</td>
                                                <td class="wrap_content">:</td>
                                                <td id="unit_asal"></td>

                                                <td>Ke</td>
                                                <td class="wrap_content">:</td>
                                                <td id="unit_tujuan"></td>
                                            </tr>
                                            <tr>
                                                <td>Oleh</td>
                                                <td class="wrap_content">:</td>
                                                <td id="pegawai_proses"></td>

                                                <td>Tanggal</td>
                                                <td class="wrap_content">:</td>
                                                <td id="tanggal_mutasi"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-bordered table-striped largeDataType" id="mutasi_detail_item">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th class="wrap_content">No</th>
                                                <th>Item</th>
                                                <th>Batch</th>
                                                <th>Satuan</th>
                                                <th>Jumlah</th>
                                                <th>Keterangan</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <b>
                                            <h6>Keterangan:</h6>
                                        </b>
                                        <p id="keterangan_tambahan"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnBatalkanMutasi">
                    <span>
                        <i class="fa fa-times-circle"></i> Batalkan Mutasi
                    </span>
                </button>
                <button type="button" class="btn btn-success" id="btnTerimaMutasi">
                    <span>
                        <i class="fa fa-check-circle"></i> Terima Mutasi
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>