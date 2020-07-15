<script type="text/javascript">
	$(function(){

		var tableLayanan = $("#table-layanan-radiologi").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Radiologi/tindakan",
				type: "GET",
				headers:{
					Authorization: "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>
				},
				dataSrc:function(response) {
					return response.response_package.response_data;
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
						return row["autonum"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<span id=\"nama_" + row["uid"] + "\">" + row["nama"] + "</span>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return row['jenis'];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/master/radiologi/layanan/edit/" + row["uid"] + "\" class=\"btn btn-warning btn-sm \" data-toggle='tooltip' title='Edit'>" +
										"<i class=\"fa fa-edit\"></i>" +
									"</a>" +
									"<button id=\"tindakan_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-tindakan\" data-toggle='tooltip' title='Hapus'>" +
										"<i class=\"fa fa-trash\"></i>" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-tindakan", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus tindakan item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Radiologi/master_radiologi_tindakan/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						//console.log(response);
						tableLayanan.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});
	});
</script>
