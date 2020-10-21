<script type="text/javascript">
	$(function(){

		var tableAntrianLabor = $("#table-antrian-labor").DataTable({
			"ajax":{
				async: false,
				url: __HOSTAPI__ + "/Laboratorium/antrian",
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
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/antrian/" + row['uid'] + "\" class=\"btn btn-warning btn-sm\">" +
                                    "<i class=\"fa fa-sign-out-alt\"></i>" +
                                "</a>" +
                                "<a href=\"" + __HOSTNAME__ + "/laboratorium/cetak/" + row['uid'] + "\" target='_blank' class=\"btn btn-primary btn-sm\">" +
                                    "<i class=\"fa fa-print\"></i>" +
                                "</a>" +
                                "<button type='button' class=\"btn btn-success btn-sm\" data-toggle='tooltip' title='Tandai selesai'>" +
                                    "<i class=\"fa fa-check\"></i>" +
                                "</a>" +
                            "</div>";
					}
				}
			]
		});
	});
</script>