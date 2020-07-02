<script type="text/javascript">
	$(function(){
		var MODE = "tambah", selectedUID;
		var tablePoli = $("#table-poli").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Poli/poli",
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
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<button id=\"poli_view_" + row['uid'] + "\" class=\"btn btn-warning btn-sm btn-detail-poli\">" +
									 	"<i class=\"fa fa-list\"></i> Detail" +
									"</button>" +
									"<a href=\"" + __HOSTNAME__ + "/master/poli/poli/edit/" + row["uid"] + "\" class=\"btn btn-info btn-sm btn-edit-poli\">" +
										"<i class=\"fa fa-edit\"></i> Edit" +
									"</a>" +
									"<button id=\"poli_delete_" + row['uid'] + "\" class=\"btn btn-danger btn-sm btn-delete-poli\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</button>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-poli", function(){
			var uid = $(this).attr("id").split("_");
			uid = uid[uid.length - 1];

			var conf = confirm("Hapus poli item?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Poli/master_poli/" + uid,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(response) {
						tablePoli.ajax.reload();
					},
					error: function(response) {
						console.log(response);
					}
				});
			}
		});

	});
</script>
