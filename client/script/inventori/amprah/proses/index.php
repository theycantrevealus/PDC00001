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
				data: function(d){
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
						return "<a href=\"" + __HOSTNAME__ + "/inventori/amprah/proses/view/" + row.uid + "\" class=\"btn btn-sm btn-info\"><i class=\"fa fa-eye\"></i></a>";
					}
				}
			]
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
						return "<button class=\"btn btn-sm btn-info print_proses_amprah\" id=\"proses_" + row.uid + "\"><i class=\"fa fa-print\"></i></button>";
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
					console.log(response);
					var data = response.response_package.response_data[0];
					$.ajax({
						async: false,
						url: __HOSTNAME__ + "/print/amprah_bukti_barang_keluar.php",
						data: {
							kode:data.kode,
							detail:data.detail
						},
						beforeSend: function(request) {
							request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
						},
						type: "POST",
						success: function(html){
							var win = window.open(document.URL, '_blank', 'location=yes,width=793.7007874,height=1122.519685,scrollbars=yes,status=yes');
							win.document.write(html);
							win.document.close();
							win.focus();
							win.print();
							win.close();
						},
						error: function(html) {
							console.log(html);
						}
					});
				},
				error: function(response) {
					console.log(response);
				}
			});
		});
	});
</script>