<script type="text/javascript">
	$(function() {
		var tablePO = $("#table-po").DataTable({
            processing: true,
            serverSide: true,
            sPaginationType: "full_numbers",
            bPaginate: true,
            lengthMenu: [[20, 50, -1], [20, 50, "All"]],
            serverMethod: "POST",
			"ajax":{
				url: __HOSTAPI__ + "/PO",
				type: "POST",
                data: function(d) {
                    d.request = "po_data";
                },
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					
					response.draw = parseInt(response.response_package.response_draw);
                    response.recordsTotal = response.response_package.recordsTotal;
                    response.recordsFiltered = response.response_package.recordsFiltered;
					return response.response_package.response_data;
				}
			},
			autoWidth: false,
			aaSorting: [[0, "asc"]],
			"columnDefs":[
				{"targets":0, "className":"dt-body-left"}
			],
            language: {
                search: "",
                searchPlaceholder: "Cari Laboratorium"
            },
			"columns" : [
				{
					"data" : null, render: function(data, type, row, meta) {
                        return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return (row.supplier !== undefined && row.supplier !== null) ? row.supplier.nama : "AUTO STOK AWAL";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tanggal_po;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"number_style\">" + number_format (row.total_after_disc, 2, ".", ",") + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return (row.sumber_dana !== undefined && row.sumber_dana !== null) ? row.sumber_dana.nama : "-";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
                            "<a href=\"" + __HOSTNAME__ + "/inventori/po/detail/" + row.uid + "\" class=\"btn btn-info btn-sm\">" +
                            "<span><i class=\"fa fa-eye\"></i>Detail<span>" +
                            "</a></div>";
					}
				}
			]
		});
		
		
		// var tablePO = $("#table-po").DataTable({
		// 	"ajax":{
		// 		url: __HOSTAPI__ + "/PO",
		// 		type: "GET",
		// 		headers:{
		// 			Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
		// 		},
		// 		dataSrc:function(response) {
		// 			console.clear();
		// 			console.log(response);
		// 			var data = response.response_package.response_data;
		// 			for(var a = 0; a < data.length; a++) {
		// 				if(data[a].supplier == undefined || data[a].supplier == null) {
		// 					data[a].supplier = {
		// 						nama: "No Data"
		// 					};
		// 				}

		// 				if(data[a].pegawai == undefined || data[a].pegawai == null) {
		// 					data[a].pegawai = {
		// 						nama: "No Data"
		// 					};
		// 				}
		// 			}
		// 			return data;
		// 		}
		// 	},
		// 	autoWidth: false,
		// 	"bInfo" : false,
		// 	aaSorting: [[0, "asc"]],
		// 	"columnDefs":[
		// 		{"targets":0, "className":"dt-body-left"}
		// 	],
		// 	"columns" : [
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
        //                 return "<h5 class=\"autonum\">" + row.autonum + "</h5>";
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.supplier.nama;
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.tanggal_po;
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return "<h6 class=\"number_style\">" + number_format (row.total_after_disc, 2, ".", ",") + "</h6>";
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return row.pegawai.nama;
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return (row.sumber_dana !== undefined && row.sumber_dana !== null) ? row.sumber_dana.nama : "-";
		// 			}
		// 		},
		// 		{
		// 			"data" : null, render: function(data, type, row, meta) {
		// 				return "<div class=\"btn-group wrap_content\" role=\"group\" aria-label=\"Basic example\">" +
        //                     "<a href=\"" + __HOSTNAME__ + "/inventori/po/detail/" + row["uid"] + "\" class=\"btn btn-info btn-sm\">" +
        //                     "<span><i class=\"fa fa-eye\"></i>Detail<span>" +
        //                     "</a></div>";
		// 			}
		// 		}
		// 	]
		// });
	});
</script>