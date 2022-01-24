<script src="<?php echo __HOSTNAME__; ?>/plugins/printThis/printThis.js"></script>
<script type="text/javascript">
	$(function() {
		var tablePO = $("#table-do").DataTable({
			processing: true,
			serverSide: true,
			sPaginationType: "full_numbers",
			bPaginate: true,
			lengthMenu: [[20, 50, -1], [20, 50, "All"]],
			serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/PO",
				type: "POST",
				data: function(d){
					d.request = "get_po_backend";
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
					response.recordsFiltered = response.response_package.recordsTotal;
					return dataSet;
				}
			},
			autoWidth: false,
			language: {
				search: "",
				searchPlaceholder: "Cari Kode PO"
			},
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.nomor_po;
					}
				},
                {
                    "data" : null, render: function(data, type, row, meta) {
                        return row.tanggal_po;
                    }
                },
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.nama_supplier;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/inventori/do/tambah/" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail\"><i class=\"fa fa-box-open\"></i> Detail</a>" +
                            "</div>";
					}
				},
			]
		});

		// var tablePo = $("#table-do").DataTable({
		// 	"ajax":{
		// 		url: __HOSTAPI__ + "/PO/all2",
		// 		type: "GET",
		// 		headers:{
		// 			Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
		// 		},
		// 		dataSrc:function(response) {
		// 			//check barang sudah sampai semua atau belum
        //             var returnedData = [];
		// 			var poData = response.response_package.response_data;
		// 			console.log(poData);
		// 			for(var CPOKey in poData) {
		// 				if(poData[CPOKey].supplier == undefined || poData[CPOKey].supplier == null) {
		// 					poData[CPOKey].supplier = {
		// 						nama: "No Data"
		// 					};
		// 				}

		// 				if(poData[CPOKey].pegawai == undefined || poData[CPOKey].pegawai == null) {
		// 					poData[CPOKey].pegawai = {
		// 						nama: "No Data"
		// 					};
		// 				}

		// 				var done_po = false;

		// 				//Check Item
		// 				var poItem = poData[CPOKey].detail;
		// 				for(var itemKey in poItem) {
		// 					if(poItem[itemKey].sampai >= poItem[itemKey].qty) {
		// 						done_po = true
		// 					} else {
		// 					    done_po = false;
		// 					    break;
        //                     }
		// 				}

		// 				if(!done_po) {
		// 				    returnedData.push(poData[CPOKey]);
        //                 }
		// 			}
		// 			return returnedData;
		// 		}
		// 	},
		// 	autoWidth: false,
		// 	aaSorting: [[0, "asc"]],
		// 	"columnDefs":[
		// 		{"targets":0, "className":"dt-body-left"}
		// 	],
		// 	"columns" : [
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.nomor_po;
		// 			}
		// 		},
        //         {
        //             "data" : null, render: function(data, type, row, meta) {
        //                 return row.tanggal_po;
        //             }
        //         },
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.supplier.nama;
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.pegawai.nama;
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
        //                     "<a href=\"" + __HOSTNAME__ + "/inventori/do/tambah/" + row.uid + "\" class=\"btn btn-info btn-sm btn-detail\"><i class=\"fa fa-box-open\"></i> Detail</a>" +
        //                     "</div>";
		// 			}
		// 		},
		// 	]
		// });








		var tableDo = $("#table-do-his").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/DeliveryOrder",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					var data = response.response_package.response_data;
					for(var a = 0; a < data.length; a++) {
						if(data[a].supplier == undefined || data[a].supplier == null) {
							data[a].supplier = {
								nama: "No Data"
							};
						}

						if(data[a].pegawai == undefined || data[a].pegawai == null) {
							data[a].pegawai = {
								nama: "No Data"
							};
						}
					}
					return data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tgl_do;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.no_do;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.supplier.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.no_invoice;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				/*{
					"data" : null, render: function(data, type, row, meta) {
						return row.status;
					}
				},*/
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<button class=\"btn btn-info btn-sm btn-detail\" id=\"detail_do_" + row.uid + "\"><i class=\"fa fa-eye\"></i> Detail</button>";
					}
				},
			]
		});

		$("body").on("click", ".btn-delete-penjamin", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus penjamin item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Penjamin/master_penjamin/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePenjamin.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

		var targetDONum;


		$("body").on("click", ".btn-detail", function () {
		    var id = $(this).attr("id").split("_");
		    id = id[id.length - 1];
            $.ajax({
                async: false,
                url: __HOSTAPI__ + "/DeliveryOrder/detail/" + id,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
                },
                type: "GET",
                success: function(response){
                    var data = response.response_package.response_data[0];
                    if(data.detail.length > 0) {
                        targetDONum = data.no_do;
                        $("#nomor-do").html(data.no_do);
                        $("#tanggal-terima").html(data.tgl_do);
                        $("#pemasok").html(data.supplier.nama);
                        $("#penerima").html(data.pegawai.nama);
                        $("#invoice").html(data.no_invoice);
                        $("#tanggal-invoice").html(data.tgl_invoice);
                        $("#keterangan-all").html((data.keterangan === "") ? "-" : data.keterangan);

                        $("#detail_do tbody").html("");
                        for(var a in data.detail) {
                            $("#detail_do tbody").append("<tr>" +
                                "<td>" + data.detail[a].autonum + "</td>" +
                                "<td>" + data.detail[a].barang.nama + "</td>" +
                                "<td>" + data.detail[a].barang.satuan_terkecil_info.nama + "</td>" +
                                "<td>" + data.detail[a].batch.batch + "</td>" +
                                "<td class=\"number_style\">" + number_format(data.detail[a].qty, 2, ".", ",") + "</td>" +
                                "<td>" + data.detail[a].kadaluarsa + "</td>" +
                                "<td>" + data.detail[a].keterangan + "</td>" +
                                "</tr>");
                        }
                        $("#form-detail-do").modal("show");
                    } else {
                        alert("Tidak ada data");
                    }
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

		$("#btn_cetak_do").click(function () {
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
                    __JUDUL__ : "Surat Terima Barang",
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
                        pageTitle: targetDONum + "",
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

		
		/*$("body").on("click", ".btn-edit-penjamin", function() {
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];
			selectedUID = uid;
			MODE = "edit";
			$("#txt_nama").val($("#nama_" + uid).html());
			$("#form-tambah").modal("show");
			return false;
		});
		
		$("#tambah-penjamin").click(function() {
			$("#txt_nama").val("");
			$("#form-tambah").modal("show");
			MODE = "tambah";
		});
		$("#btnSubmit").click(function() {
			var nama = $("#txt_nama").val();
			if(nama != "") {
				var form_data = {};
				if(MODE == "tambah") {
					form_data = {
						"request": "tambah_penjamin",
						"nama": nama
					};
				} else {
					form_data = {
						"request": "edit_penjamin",
						"uid": selectedUID,
						"nama": nama
					};
				}
				$.ajax({
					async: false,
					url: __HOSTAPI__ + "/Penjamin",
					data: form_data,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type: "POST",
					success: function(response){
						$("#txt_nama").val("");
						$("#form-tambah").modal("hide");
						tablePenjamin.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});*/

	});
</script>

<div id="form-detail-do" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-large-title" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                        <td id="nomor-do"></td>

                        <td>Tanggal Terima</td>
                        <td class="wrap_content">:</td>
                        <td id="tanggal-terima"></td>

                        <td>Pemasok</td>
                        <td class="wrap_content">:</td>
                        <td id="pemasok"></td>
                    </tr>
                    <tr>
                        <td>Diterima Oleh</td>
                        <td>:</td>
                        <td id="penerima"></td>

                        <td>Invoice</td>
                        <td>:</td>
                        <td id="invoice"></td>

                        <td>Tanggal Invoice</td>
                        <td>:</td>
                        <td id="tanggal-invoice"></td>
                    </tr>
                </table>
				<table class="table table-bordered largeDataType" id="detail_do">
                    <thead class="thead-dark">
                        <tr>
                            <th class="wrap_content">No</th>
                            <th style="width: 20%">Barang</th>
                            <th>Satuan</th>
                            <th class="wrap_content">Batch</th>
                            <th class="wrap_content">Qty</th>
                            <th>Kedaluarsa</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <b>Keterangan:</b><br />
                <span id="keterangan-all"></span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Kembali</button>
				<button type="button" class="btn btn-primary" id="btn_cetak_do">Cetak</button>
			</div>
		</div>
	</div>
</div>