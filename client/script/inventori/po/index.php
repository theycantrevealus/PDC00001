<script type="text/javascript">
	$(function() {
		var tablePO = $("#table-po").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/PO",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
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
						return row.supplier.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.tanggal_po;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<h6 class=\"text-right\">" + number_format (row.total_after_disc, 2, ".", ",") + "</h6>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row.pegawai.nama;
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<a href=\"" + __HOSTNAME__ + "/inventori/po/detail/" + row["uid"] + "\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-eye\"></i></a>";
					}
				}
			]
		});
	});
</script>