<script type="text/javascript">
	$(function(){
		/*$.ajax({
			async: false,
			url: __HOSTAPI__ + "/Terminologi/child/" + __PAGES__[2],
			beforeSend: function(request) {
				request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
			},
			type: "GET",
			success: function(response){
				console.log(response);
			}
		});*/

		var tableTerminologiItem = $("#table-terminologiItem").DataTable({
			"ajax":{
				url: __HOSTAPI__ + "/Terminologi/child/" + __PAGES__[2],
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
						return row["id"];
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<a href=\"" + __HOSTNAME__ + "/terminologi/child/" + row["id"] + "\">" + row["nama"] + "</a>";
					}
				},
				{
					"data" : null, render: function(data, type, row, meta) {
						return "<div class=\"btn-group\" role=\"group\" aria-label=\"Basic example\">" +
									"<a href=\"" + __HOSTNAME__ + "/terminologi/edit_child/" + row["id"] + "\" class=\"btn btn-info btn-sm\">" +
										"<i class=\"fa fa-pencil\"></i> Edit" +
									"</a>" +
									"<button id=\"delete_" + row['id'] + "\" class=\"btn btn-danger btn-sm btn-delete-terminologi\">" +
										"<i class=\"fa fa-trash\"></i> Hapus" +
									"</a>" +
								"</div>";
					}
				}
			]
		});

		$("body").on("click", ".btn-delete-terminologi", function(){
			var id = $(this).attr("id").split("_");
			id = id[id.length - 1];

			var conf = confirm("Hapus terminologi?");
			if(conf) {
				$.ajax({
					url:__HOSTAPI__ + "/Terminologi/terminologi_item/" + id,
					beforeSend: function(request) {
						request.setRequestHeader("Authorization", "Bearer " + <?php echo json_encode($_SESSION["token"]); ?>);
					},
					type:"DELETE",
					success:function(resp) {
						tableTerminologiItem.ajax.reload();
					}
				});
			}
		});
	});
</script>